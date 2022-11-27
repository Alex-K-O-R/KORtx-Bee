<?php
namespace app;

use app\dba\DBModificationContext;
use app\dba\UserDBA;
use app\filters\models\simple_UserFLTR;
use app\filters\models\user_control_FLTR;
use app\models\UserMDL;
use app\pages\Pages;
use app\utilities\inner\Array_;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;
use app\dba\ModelProcessor;
use app\utilities\KCAPTCHA;

class WebUIApplication extends Application {
    const domain = '{site_url}';

    const SESSION_CAPTCHA_BLOCK = 'captcha';

    public static $PROFILER_MODE = 0;


    public function preloadEnv($path = null, $get = null, $post = null, $file = null)
    {
        parent::preloadEnv($path, $get, $post, $file);
    }

    final function refreshSessionObjects($User = null){
        if($User){
            $this->refreshSessionDiv(Application::SESSION_USER_INFO_BLOCK, $User);
        }
    }

    private function ThemeSelector(){
        if($this->getUserInfo()){
            return 'reg';
        } else {
            return 'cmn';
        }
    }

    /**
     * @return UserMDL|void
     */
    final function getUserInfo()
    {
        return parent::getUserInfo();
    }

    function loadPage($path = null, $get = null, $post = null, $file = null){
        $processedUrl = $this->processUrl($path);
        switch($processedUrl['mask']){
            case Pages::main:
                $this->Profiler->Start();
                //$this->Page()->UseTheme('reg')->Display('/main/index.php');
                $this->Page()->UseTheme($this->ThemeSelector())->Display('/main/index.php');
                $this->Profiler->Stop();
                break;



            case Pages::about:
                $this->Profiler->Start();
                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Информация о KORtx Bee', 'EN'=>'About KORtx Bee'))
                );
                $this->Page()->UseTheme($this->ThemeSelector())->Display('/about/index.php');
                $this->Profiler->Stop();
                break;




            case Pages::user_list:
                $this->Profiler->Start();
                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Список участников', 'EN'=>'Participant\'s list'))
                );

                $page = CIS::l($get, "page", 1);
                if(!$page) $page = 1;
                $page_vol = 3;

                $Filter = null;
                if($this->isSearchDefined($get)){
                    $Filter = (new simple_UserFLTR($get))->EscapeValues($this->getUserDBA());
                    $this->Page()->Properties()->addVar('filter', $Filter);
                }

                $UserCount = $this->getUserDBA()->getCountTopUserSQL($Filter);
                $Users = Array_::varToArray(ModelProcessor::loadModelsForLanguage(
                    UserMDL::TYPE(),
                    $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds($page_vol,$page,$Filter),
                    $this->getUserDBA(),
                    $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                ));

                $FavoriteIds = null;
                if($this->getUserInfo()){
                    $FavoriteIds = $this->getWebPortalDBA()->getFavoriteUsersIdsForUser($this->getUserInfo()->getUserId());
                }

                $this->Page()->Properties()->addVar('users', $Users);
                $this->Page()->Properties()->addVar('favorite_ids', $FavoriteIds);
                $this->Page()->Properties()->addVar('totals', $UserCount);
                $this->Page()->Properties()->addVar('page', $page);
                $this->Page()->Properties()->addVar('page_volume', $page_vol);

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/users/index.php');
                $this->Profiler->Stop();
                break;


            case Pages::user_info:
                $this->Profiler->Start();
                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Информация об участнике', 'EN'=>'Participant\'s profile'))
                );

                $Uid = $processedUrl['params'][0];

                $User = ModelProcessor::loadModelsForLanguage(
                    UserMDL::TYPE(),
                    $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds(1,0,null, $Uid),
                    $this->getUserDBA(),
                    $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                );

                $FavoriteIds = null;
                if($this->getUserInfo()){
                    $FavoriteIds = $this->getWebPortalDBA()->getFavoriteUsersIdsForUser($this->getUserInfo()->getUserId());
                }
                $this->Page()->Properties()->addVar('favorite_ids', $FavoriteIds);
                $this->Page()->Properties()->addVar('user', $User);

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/users/show/index.php');
                $this->Profiler->Stop();
                break;





            case Pages::login:
                $this->Profiler->Start();
                $login = trim(CIS::l($post, 'login', null));
                if ($post) {
                    if ($login && CIS::l($post, 'password') && $this->getUserDBA()->checkAuth($post['login'], $post['password'])) {
                        $User = $this->getUserDBA()->getUserInfoByLogin($login);
                        /**
                         * @var $User UserMDL
                         */
                        if($User) $User = ModelProcessor::loadModelsForLanguage(
                            UserMDL::TYPE(),
                            $User,
                            $this->getUserDBA(),
                            $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                        );

                        if($User){
                            if($User->getActivated()){
                                if(!$User->getIsBlocked()){
                                    $this->updateGlobalFilter($User->getLastSelectedLangId());
                                    $this->refreshSessionObjects($User);
                                    $this->getUserDBA()->refreshLastLoginDate($User->getSecId());
                                    if(!CIE::l($this->getUserInfo()->getDName())){
                                        $this->RedirectTo(Pages::getDynamic(Pages::personal_edit));
                                    }
                                    $this->RedirectTo(Pages::personal_cabinet);
                                } else $this->Page()->Properties()->setState(Application::AUTH_BLOCKED);

                            } else $this->Page()->Properties()->setState(Application::AUTH_NO_ACTIVATION);
                        }
                    } else $this->Page()->Properties()->setState(Application::AUTH_NO_ACCESS);
                }


                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Авторизация', 'EN'=>'Authorisation'))
                );
                $this->Page()->Properties()->addVar(
                    'login', $login
                );

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/login/index.php');
                $this->Profiler->Stop();
                break;


            case Pages::logout:{
                $this->Profiler->Start();
                $this->destroySession();
                $this->RedirectTo(Pages::main);
                $this->Profiler->Stop();
            }
                break;


            case Pages::register:{
                $this->Profiler->Start();
                include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/captcha/kcaptcha.php');
                $captcha = null;

                if($post){
                    $login = CIS::l($post, 'login', null);
                    $this->Page()->Properties()->addVar('login', $login);
                    if ($login) if (CIS::l($post, 'password') === CIS::l($post, 'verify_password', null)) {
                        if(CIS::l($post, 'captcha', null)==$this->getSessionDiv(self::SESSION_CAPTCHA_BLOCK)){
                            $login = trim($login);
                            if(!$this->getUserDBA()->checkIfLoginExists($login)){
                                $modCntxt = new DBModificationContext(0, null,$this->getUserDBA());
                                $newUsr = $this->getUserDBA()->addUser($login, $post['password'], $modCntxt);

                                if($newUsr) {
                                    $this->Page()->Properties()->setState('success');
                                    $this->getUserDBA()->updateUserPreferredLanguage($newUsr, $this->getGlobalFilter()->getPortalLanguageInfo()->getAcronym());
                                }
                            } else $this->Page()->Properties()->setState('exists');
                        } else $this->Page()->Properties()->setState('missmatch');
                    } else $this->Page()->Properties()->setState('misstype');
                }

                $captcha = new KCAPTCHA();
                $this->refreshSessionDiv(self::SESSION_CAPTCHA_BLOCK, $captcha->getKeyString());

                $this->Page()->Properties()->addVars(
                    array('captcha' => $captcha->render())
                );

                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Регистрация', 'EN'=>'Registration'))
                );
                $this->Page()->UseTheme($this->ThemeSelector())->Display('/login/register/index.php');
                $this->Profiler->Stop();
            }
                break;



            case Pages::personal_cabinet:
                $this->Profiler->Start();
                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Личная страница', 'EN'=>'Personal page'))
                );

                $User = ModelProcessor::loadModelsForLanguage(
                    UserMDL::TYPE(),
                    $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds(1,0,null, $this->getUserInfo()->getUserId()),
                    $this->getUserDBA(),
                    $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                );

                $page = CIS::l($get, "page", 1);
                if(!$page) $page = 1;
                $page_vol = 3;

                $FavoriteIds = $this->getWebPortalDBA()->getFavoriteUsersIdsForUser($this->getUserInfo()->getUserId());
                if(count($FavoriteIds)) {
                    $Favorites = ModelProcessor::loadModelsForLanguage(
                        UserMDL::TYPE(),
                        $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds($page_vol,$page,null, $FavoriteIds),
                        $this->getUserDBA(),
                        $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                    );
                    $this->Page()->Properties()->addVar('totals', count($FavoriteIds));
                    $this->Page()->Properties()->addVar('page', $page);
                    $this->Page()->Properties()->addVar('page_volume', $page_vol);
                    $this->Page()->Properties()->addVar('favorite_users' ,$Favorites);
                }


                $this->Page()->Properties()->addVar('user', $User);

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/account/index.php');
                $this->Profiler->Stop();
                break;


            case Pages::personal_edit:
                $this->Page()->Properties()->setProps(
                    $this->Translater(array('RU'=>'Изменение личной информации', 'EN'=>'Personal info editor'))
                );

                if($post){
                    $modCntxt = new DBModificationContext(
                        $this->getUserInfo()->getUserId()
                        , $this->getUserInfo()->getUserId()
                        , $this->getUserDBA()
                    );

                    $field = CIS::l($post, 'uname', null);
                    $field = ($field != $this->getUserInfo()->getDName()) ?
                        $this->getUserDBA()->updateName(
                            $this->getUserInfo()->getUserId()
                            , $field, $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            , $modCntxt
                        ) : $field;

                    $field = CIS::l($post, 'usname', null);
                    $field = ($field != $this->getUserInfo()->getDSurname()) ?
                        $this->getUserDBA()->updateSurname(
                            $this->getUserInfo()->getUserId()
                            , $field, $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            , $modCntxt
                        ) : $field;

                    $field = CIS::l($post, 'job', null);
                    $field = ($field != $this->getUserInfo()->getDProfession()) ?
                        $this->getUserDBA()->updateProfession(
                            $this->getUserInfo()->getUserId()
                            , $field, $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            , $modCntxt
                        ) : $field;

                    $field = CIS::l($post, 'general', null);
                    $field = ($field != $this->getUserInfo()->getDGeneralInfo()) ?
                        $this->getUserDBA()->updateGeneralInfo(
                            $this->getUserInfo()->getUserId()
                            , $field, $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            , $modCntxt
                        ) : $field;

                    $field = CIS::l($post, 'details', null);
                    $field = ($field != $this->getUserInfo()->getDAdditionalInfo()) ?
                        $this->getUserDBA()->updateAdditionalInfo(
                            $this->getUserInfo()->getUserId()
                            , $field, $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            , $modCntxt
                        ) : $field;

                    $field = CIS::l($post, 'langdef', null);
                    $field = ($field != $this->getUserInfo()->getLastSelectedLangId()) ?
                        $this->getUserDBA()->updateUserPreferredLanguage(
                            $this->getUserInfo()->getUserId()
                            , $field
                        ) : $field;

                    $usrPic = CIS::l($file,'avatar', null);
                    if($usrPic)
                        $this->getUserDBA()->updateUserAvatarPath(
                            $this->getUserInfo()
                            , $usrPic['tmp_name']
                            , $modCntxt);

                    $User = ModelProcessor::loadModelsForLanguage(
                        UserMDL::TYPE(),
                        $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds(1,0,null, $this->getUserInfo()->getUserId()),
                        $this->getUserDBA(),
                        $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                    );

                    $this->refreshSessionObjects($User);
                    $this->Page()->Properties()->setState('data-saved');
                }

                /**
                 * @var $User UserMDL
                 */
                $User = ModelProcessor::loadModelsForLanguage(
                    UserMDL::TYPE(),
                    $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds(1,0,null, $this->getUserInfo()->getUserId()),
                    $this->getUserDBA(),
                    $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                );

                if(!$this->getUserInfo()->getLastLoginDate() && !($this->getUserInfo()->getDName() || $this->getUserInfo()->getDSurname())) {$this->Page()->Properties()->setState('novice');}

                $this->Page()->Properties()->addVar('user', $User);

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/account/edit_personal/index.php');
                $this->Profiler->Stop();

                break;



            case Pages::user_control:
                $this->Profiler->Start();

                if($post){
                    if($this->getUserInfo()->getIsAdmin()){
                        $toActivate = CIS::l($post, 'act', null);
                        $toDeblock = CIS::l($post, 'deblk', null);
                        $toBlock = CIS::l($post, 'blk', null);
                        $toDelete = CIS::l($post, 'del', null);

                        $modCntxt = new DBModificationContext(
                            $this->getUserInfo()->getUserId()
                            , null, $this->getUserDBA()
                        );

                        if($toActivate){
                            foreach($toActivate as $userId){
                                $modCntxt->changeTargetEntIdTo($userId);
                                $this->getUserDBA()->activateSecurityAcc($userId, $modCntxt);
                            }
                        }

                        if($toBlock){
                            foreach($toBlock as $userId){
                                $this->getUserDBA()->setUserBlock($userId, true, $modCntxt->changeTargetEntIdTo($userId));
                            }
                        }

                        if($toDeblock){
                            foreach($toDeblock as $userId){
                                $this->getUserDBA()->setUserBlock($userId, false, $modCntxt->changeTargetEntIdTo($userId));
                            }
                        }

                        if($toDelete) {
                            foreach($toDelete as $userId){
                                $this->getUserDBA()->deleteUserById($userId, $modCntxt->changeTargetEntIdTo($userId));
                            }
                        }

                    }
                }

                $Filter = (new user_control_FLTR($get))->EscapeValues($this->getUserDBA());
                $this->Page()->Properties()->addVar('filter', $Filter);

                $UserCount = $this->getUserDBA()->getCountTopUserSQL($Filter);
                $Users = Array_::varToArray(ModelProcessor::loadModelsForLanguage(
                    UserMDL::TYPE(),
                    $UserList = $this->getUserDBA()->getUsersInfoesByUsersIds(3,0,$Filter),
                    $this->getUserDBA(),
                    $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                ));

                $this->Page()->Properties()->addVar('users', $Users);
                $this->Page()->Properties()->addVar('totals', $UserCount);

                $this->Page()->UseTheme($this->ThemeSelector())->Display('/admin_panel/user_control/index.php');
                $this->Profiler->Stop();
                break;


            case Pages::site_settings:
                $this->Profiler->Start();
                if (CIE::l($post)) {
                    if(CIS::l($post, 'use-only-language', null)!==null){
                        $this->updateGlobalFilter($post['use-only-language']);
                        if($this->getUserInfo()){
                            $User = ModelProcessor::loadModelsForLanguage(
                                UserMDL::TYPE(),
                                $this->getUserDBA()->getUserInfoByLogin($this->getUserInfo()->getLogin()),
                                $this->getUserDBA(),
                                $this->getGlobalFilter()->getPortalLanguageInfo()->getLangId()
                            );
                            if($User) $this->refreshSessionObjects($User);
                        }
                        echo(json_encode('language-was-successfully-changed', true));
                        exit;
                    }
                }
                $this->Profiler->Stop();
                break;


            case Pages::add_favorites:
                if(!$this->getUserInfo() || !$post) exit;

                $whom = CIS::l($post, 'whom', null);
                $whomId = CIS::l($post, 'whomId', null);

                if(!$whom && !$whomId) exit;

                switch($whom){
                    case 'user':{
                        $existed = $this->getWebPortalDBA()->getFavoriteUsersIdsForUser($this->getUserInfo()->getUserId());

                        if(!$existed || $existed && (count($existed) == 0 || !in_array($whomId, $existed))){
                            print json_encode(
                                $this->getWebPortalDBA()->addFavoriteUserForUser(
                                    $this->getUserInfo()->getUserId(), $whomId
                                ), true
                            );
                        } else {
                            print json_encode(
                                $this->getWebPortalDBA()->removeFavoriteUserForUser(
                                    $this->getUserInfo()->getUserId(), $whomId
                                ), true
                            );
                        }
                    } break;
                }
                exit;
                break;



            case Pages::db_client:
                if($this->getUserInfo() && $this->getUserInfo()->getIsAdmin())
                    include_once($_SERVER['DOCUMENT_ROOT'].'/utilities/dbclient/adminer.php');
                else $this->RedirectTo('404');
                break;

            default:
                $this->Profiler->Start();
                self::__404Header();
                $this->Page()->UseTheme($this->ThemeSelector())->Display('/404/index.php');
                $this->Profiler->Stop();
                break;
        }
    }

}
