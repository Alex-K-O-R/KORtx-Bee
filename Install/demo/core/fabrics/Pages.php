<?php
namespace app\pages;

class Pages extends PageFinder {
    const main = '/';

    const about = '/about/';

    const user_list = '/participants/';
    const user_info = '/participant/*/';
    const user_control = '/admin/participants/';

    const personal_cabinet = '/my_space/';
    const personal_edit = '/my_space/edit/';
    const personal_favorites = '/my_space/favorites/';

    const add_favorites = '/fav/';
    const site_settings = '/settings/';

    const login = '/login/';
    const logout = '/logout/';
    const register = '/register/';

    const db_client = '/utility/dbclient/';
}