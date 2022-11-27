<?
use app\pages\Pages;

/**
 * @var app\pages\Page $this
 */
/**
 * @var app\WebUIApplication $App
 */
$App = $this->Application();
?>
<div class="content-wrapper">
    <div class="nav__tr">
        <ul class="tab-menu breadcrumbs">
            <li><a href="<?=Pages::main?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Home'))?></a></li>
            <li><?=$App->Translater(array('RU'=>'О проекте KORtx', 'EN'=>'About KORtx'))?></li>
        </ul>
    </div>
    <br/>
    <div class="static">
    <div class="tab-content active col-lg-12 about-project">
    <div class="one-tr">
    <h2>Briefly.</h2>
    <h3>Kind of beast it is?</h3>
    <p>
        KORtx Bee &mdash; KORtx <b>B</b>ack<b>e</b>nd <b>e</b>ngine - is a comfy MVC-framework for creating and transferring sites with ease. It has the real perspectives of automation for view models description, <b>language and other applied models</b>, database requests.
    </p>
    <p>
        The ideology's based on a fundamental rejection of the hypercomplicity of writing and maintaining code, which is a common suffer for some popular modern frameworks unreasonably.
        I guess, every programmer from time to time has come across solutions left behind by "burnt-out programmer gurus" who got bogged down or had already left to obstruct other projects,
        because they could not handle with their own creation anymore due to excessive complication.
        Such processes are not rare, they have different causes, and their observation is rather frustrating.
    </p>
    <p>
        Such reality is hardly relevant to progress in IT sphere. More kind of slipping and degradation.
    </p>
    <p>
        KORtx Bee is built up with hope to solve that problem.
    </p>
    <p>
        Like any other framework, KORtx Bee offers a certain structure and a set of rules that should be followed across project development.
        The engine has an articulate division by classes: pages, themes, models and controllers by application points, as well as 5 main directories:
    <ul>
        <li>constants - important "global" constants that can affect the operation of the engine, or does not bounded to a specific class;</li>
        <li>core\dba\classes - main mechanics of database access;</li>
        <li>core\dba\models - descriptions of simple and dialect-depending models;</li>
        <li>display\ - so called "views". Css, js, themes, static images, i.e. "client side";</li>
        <li>utilities\ - depending on the location of the directory, it could be inner helper classes
            (including kernel functions), or third-party libraries that can come in handy anywhere.</li>
    </ul>
    KORtx Bee is joyful for quick creation of a compact web application (as an example, for single board computers), and for implementing much larger complex solutions with API functions.
    Any of existing frameworks can hardly boast of such a sharp division into server and client parts.
    </p>
    <h2>Thorough.</h2>
    <h3>Basics.</h3>
    <p>
        Typical project based on KORtx Bee is an Interface (web or software) built by inheritance from the root Application class.
        The Application class is the engine core that provides a toolkit for working with sessions, pages and their properties, processing requests, and much more.
    </p>
    <p>
        For example, this demo site is aggregated in the WebUI.php file, which provides the basic mechanics and represents a set
        of all controllers (cases like case Pages::main).
        Each case &mdash; currently, the essence of the controller in MVC. Mostly, workflow happens here. Depending on the complexity of the project,
        controllers can be divided/grouped into getters, includes, traits, or separate classes inherited from the same Application.
    </p>
    <p>
        The specified controllers are directly connected with the address system, which is described in the
        app\pages\pages segment. The constants in Pages segment are the url masks that are being used in the WebUI.php controllers in this example.
        Thus, a typical scheme for creating a new page has already became visible:
    <ul>
        <li>1. Come up with the url-mask;</li>
        <li>2. In WebUI add the case for new url-mask;</li>
        <li>3. In the case, data preparation / processing takes place, then everything is displayed like this:<br/>
            $this -> Page() -> UseTheme('cool-one') -> Display('/service/additionals/index.php');</li>
    </ul>
    Clearly visible in case of Pages::user_info: { ... } from WebUI.php &mdash; page's displaying user profile.
    </p>
    <h3>Models implementation.</h3>
    <p>
        Based on the MVC principle, M is used to link together V and C, i.e. a model for data transfer.
        Models are available in the app\models namespace. According to the rules specified in KORtx Bee, all models are divided onto dialect-depending entities (built on the basis of DynamicsModelContainer)
        and other entities independent of the system language. Dialect-depending models are those models that contain at least one dialect-depending field, i.e. field, the content of which should change when user changes the preferred language of the site.
        The model type is being chosen taking the namespace into credit, based on the specific task.
        For keeping order, it is suggested that all model class names should have the MDL suffix.
    </p>
    <p>
        All existing models stored in core/models/ directories.
    </p>
    <p>
        99% of the methods in models are getters. These are allow you to get all the information that the object/model instance has. Sometimes these getters have a minimal wrapper for convenience (for example, a getter with a date format parameter).
        Any methods, except for getting fields, or improving usability while working with a model, are prohibited, because this is DIRECTLY CONTRADICTING TO THE MVC PRINCIPLE AND LEADS TO A GARBAGE.
    </p>
    <h3>Language model implementation.</h3>
    <p>
        KORtx Bee has a default language model. It propagates to dynamic and static contexts.
        A dynamic context includes entities with dialect-depending fields that can be uncontrollably changed by system participants.
        For example, user (UserMDL model) may have a field of *about information*, the content of which may be different for different languages and it could be changed by the initiative of some user.
        A static context refers to the content of web pages that is not changed by users.
    </p>
    <p>
        Dynamic context operates the language identifier and the models Processor. The language description matches the entry in *_languages table in the database, as well as the global environment filter: Application->getGlobalFilter()->getPortalLanguageInfo().
    </p>
    <p>
        The static context operates with the acronym of the language and, by your wish: arrays of translations, or pages/elements with a deterministic language affiliation.
    </p>
    <h4>Dynamic context and dialect-depending models.</h4>
    <p>
        All dialect-depending models are derived from the DynamicsModelContainer class and being built according to certain principle for further use by the ModelProcessor.
    </p>
    <p>
        The dynamic container object is required to "highlight" the fields that depend on selected language. "Highlighting" is carried out by setting an array of indexes in the descriptor class. Indexes correspond to:
    <ul>
        <li>the order number of the field within a result of obtaining data from the database;
        <li>the order of field initialization in model's constructor.</li>
    </ul>
    In this case, there is a restriction: the order of the fields in the constructor after setting the indexes IS ALLOWED TO CHANGE ONLY TAKING INTO ACCOUNT THE INDEXES! Otherwise, instead of desired text, some neighboring field is to be displayed or changed.
    </p>
    <p>
        Here is the general principle for creating a dialect-depending model:
    <ul>
        <li>determination of the actual fields in the database (it is recommended to mark dialect-depending fields with the d_* prefix);
        </li>
        <li>creation of the general DBA function for obtaining the actual fields (see the usage of IModelDataProvider interface) involved in model building, by setting up the text list of these fields (Attention! Without "*" selector);
        </li>
        <li>description of model fields within model class;</li>
        <li>constructor declaration, taking into account the order of the fields as a result of a query from the database;</li>
        <li>enumeration of dialect-depending field's indexes for DynamicsModelContainer.</li>
    </ul>
    Example of a dialect-depending model description is the UserMDL entity. Content for these is obtained by a general SQL query from UserDBA, which provides field order matching in the DynamicsModelContainer constructor and descriptor.
    </p>
    <p>
        The description of preprocessor call to build the finally formed model matching the selected language is given below:
        <br/>
        $USERS = ModelProcessor::loadModelsForLanguage(<br/>
    <ul>
        <li>UserMDL::TYPE(), // type of the models that are being returned by preprocessor</li>
        <li>$UserList = $this->getUserDBA()->getUsersInfo...(), // retrieving source data array from DB</li>
        <li>$this->getUserDBA(), // dba provider used to retrieve the data (in general, it may be a separate provider)</li>
        <li>$this->getGlobalFilter()->getPortalLanguageInfo()->getLangId() // identifier of the language, for which the final models will be built</li>
    </ul>
    );
    </p>
    <h4>Static context and Application capabilities</h4>
    <p>
        Static context is the content of web pages that's bind to the selected language, but has no variation other than the ones
        determined by the creators of the site. For example, links in the site menu, the general title of the catalog, the name of the form field, etc. As previously reported, a static context operates with a language acronym,
        as well as two features "stated" in the core of KORtx Bee: arrays of translations, or pages with a deterministic language affiliation.
    </p>
    <p>
        The first option is used when only a small part of the information blocks in the display is language dependent.
        Any Interface built on the basis of the Application class has an Application->Translater method available, where key-value pairs for the languages ​​existing in the system should be passed.
        As follows:
        <br/>
        < ? =$App->Translater(array('RU'=>'Новости', 'EN'=>'Latest news')) ? >
    </p>
    <p>
        The second option is used if the bulk of the page text is tied to the language, or there is a need to make different accents on the page, depending on the cultural bases of site visitor.
        Examples are sections that tell you about the history of a particular company, texts dedicated to documentation of API functions / parts of a web service like this one you're currently reading.
        In this case, insert arrays are inconvenient to use, and pages or views with a deterministic language affiliation come to the rescue.
    </p>
    <p>
        KORtx Bee framework uses the so-called. "views" for rendering, i.e. files of site web pages that contain information part minus general and thematic elements of the site: "headers" and "footers",
        special menu blocks which are available in many places, and other shown-across rendering elements. Usually, each view is located in a separate directory stored inside general pages directory, for example:
        /pages/about/index.php. The specified index.php file with the page body will be loaded <b>by default</b> if a link to it is specified from any controller.
    </p>
    <p>
        However, it is possible to place additional views in subdirectories corresponding to language acronyms relative to the default view.
        For example, the view that provides the text you're looking at has the following structure:
        /pages/about/
        EN/index.php - english text (optional)
        RU/index.php - russian text (optional)
        index.php - default view containing information about missing translation, that shall be displayed, if later (tomorrow, or in a year) some other systems languages would be described and selected.
    </p>
    <h3>Views implementation.</h3>
    <p>
        Everything's related to rendering (i.e. everything that is intended for the client side) is located in display section, which has nested directories for a simple and understandable subdivision into typed directions: css, img, js, ...
    </p>
    <p>
        Attention should be paid to the next folders:
    <ul>
        <li>general - themes are described there, as well as general templates, such as header and footer;</li>
        <li>nodes - it contains repeating block elements based on php and js: menus, editors;</li>
        <li>pages - directory containing pages/views being used in controllers.</li>
    </ul>
    </p>
    <p>
        In any view's body, depending on the context, Page object is available in $this pointer. The Page has easy-to-use tools: variable storage, status field, header/title descriptors. Through the Page properties, you can also get to the level of the Application itself.
        There are enough examples of working with the Page object even in this demo site.
    </p>
    <h3>Directories structure.</h3>
    <p>
        Let's consolidate the structure of files and directories.
    </p>
    <p>
        In site root there are usually located at least 4 files:
    <ul>
        <li>.htaccess - tuned to the needs of KORtx Bee engine and traditional SEO rules;</li>
        <li>index.php - is a proxy for allowing multiple applications to work, such as a public web service and an API section, depending on the presence of the api.* in subdomain of the original request;</li>
        <li>Loader.php - loader of the main KORtx Bee framework libraries for one or several applications;</li>
        <li>WebUI.php / API.php / AdminUI.php - controller of one or more interfaces, which is connected in index.php</li>
    </ul>
    </p>
    <p>
        For the "main" directories descriptions are available in the short description of the engine above. Duplicating:
    <ul>
        <li>constants - important "global" constants that can affect the operation of the engine, or does not bounded to a specific class;</li>
        <li>core\dba\classes - main mechanics of database access;</li>
        <li>core\dba\models - descriptions of simple and dialect-depending models;</li>
        <li>display\ - so called "views". Css, js, themes, static images, i.e. "client side";</li>
        <li>utilities\ - depending on the location of the directory, it could be inner helper classes
            (including kernel functions), or third-party libraries that can come in handy anywhere.</li>
    </ul>
    </p>
    <h3>Database interaction.</h3>
    <p>
        Typed access objects are used to interact with the database in this system. All of them has to be located in the /core/dba/ directories, distinguished by the DBA suffix and have a common DBAccess ancestor.
    </p>
    <p>
        Frequently used database access objects may be initialized with Application, others could be created in controllers on demand. Everything is quite simple here.
    </p>
    <p>
        All DBA providers inherited from DBAccess have ...DBA->query() method. The first parameter is the SQL query, the second is the type of the result.
        The return types most commonly used are 'row' and 'arr' (suitable for ModelProcessor). The main thing is to always remember in further work what exactly you received: if you passed 'row', then you will receive an "object"; if 'arr', then an array of "objects".
    </p>
    <p>
        Getting source information is carried out by a method with a logical name from a suitable DBA object. Attention! Requesting information about an object
        SHOULD ALWAYS BE BUILT "AROUND" THE BASIC SQL-query CONTAINING THE EXHAUSTIVE LIST OF FIELDS TO GET.
        To do this, it is always recommended to describe the database access provider from the IModelDataProvider interface &mdash; it "forces" to implement such a query.
        This approach allows you to take full advantage and all capabilities of the KORtx Bee core and is guaranteed to simplify automation and support in the future.
    </p>
    <p>
        For dialect-depending fields, a common method has been implemented for the convenience of ongoing changes. Since the method implies the transfer of an entity type, it was considered appropriate to replicate it to every typed DBAs by copying. However, nothing prevents you from separate this method into the common ancestor.
    </p>
    <h3>Logs, debug and profiling.</h3>
    <p>
        KORtx Bee has some built-in profiling and debugging capabilities. Enabling/disabling is currently carried out from the ICoreSettings interface, by changing the values of constants.
    </p>
    <p>
        Debug logs (errors of SQL and Application itself are being logged) are stored in text files with detailed information for troubleshooting. The profiling results also end up in a separate text file. By default, the measurer creates a record
        only by exceeding the emergency interval setting at the stages of data preparation and page formation. Emergency setting is set when the Profiler class is initialized; initialization occurs automatically at the stage of loading the Application.
    </p>
    <p>
        In addition to debugging, changelogs are also available. They handle the information about change's time, target object identifier, source of the change, and descriptions.
    </p>
    <p>
        KORtx Bee provides 2 types of logging with help of LogDBA class: with modification context (when user modifies the specific entity) and without it (some script works).
    </p>
    <p>
        Modification context is provided by DBModificationContext instance, which can be passed to LogDBA method, as well as being adjusted for a particular entity in case of batch changes.
    </p>
    <p>
        Changelog entries go to the database, by default &mdash; to the general one. These records must have a significance level that is defined in the specific setter method from DBA.
        The different significance levels in perspective would easily allow you to organize different store intervals for significant and less interesting changes records in cases of manual or automatic cleansing.
    </p>
    <h3>All in all.</h3>
    <p>
        If you are reading this paragraph, then you have already got the idea about KORtx Bee framework and now you can try to implement something. For example, a new page with the traditional "Hello World!".
        Or maybe immediately more serious &mdash; a new model and its provider .. In any case, I wish you success!
    </p>










    </div>
    </div>
    </div>
</div>