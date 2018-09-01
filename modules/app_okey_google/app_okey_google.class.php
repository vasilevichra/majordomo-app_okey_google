<?php
/**
 * Okey google
 * @package project
 * @author Wizard <sergejey@gmail.com>
 * @copyright http://majordomo.smartliving.ru/ (c)
 * @version 0.1 (wizard, 23:08:56 [Aug 31, 2018])
 */
//
//
class app_okey_google extends module
{
    /**
     * app_okey_google
     *
     * Module class constructor
     *
     * @access private
     */
    function __construct()
    {
        $this->name = "app_okey_google";
        $this->title = "Okey google";
        $this->module_category = "<#LANG_SECTION_APPLICATIONS#>";
        $this->checkInstalled();
    }

    /**
     * saveParams
     *
     * Saving module parameters
     *
     * @access public
     */
    function saveParams($data = 1)
    {
        $p = array();
        if (IsSet($this->id)) {
            $p["id"] = $this->id;
        }
        if (IsSet($this->view_mode)) {
            $p["view_mode"] = $this->view_mode;
        }
        if (IsSet($this->edit_mode)) {
            $p["edit_mode"] = $this->edit_mode;
        }
        if (IsSet($this->tab)) {
            $p["tab"] = $this->tab;
        }
        return parent::saveParams($p);
    }

    /**
     * getParams
     *
     * Getting module parameters from query string
     *
     * @access public
     */
    function getParams()
    {
        global $id;
        global $mode;
        global $view_mode;
        global $edit_mode;
        global $tab;
        if (isset($id)) {
            $this->id = $id;
        }
        if (isset($mode)) {
            $this->mode = $mode;
        }
        if (isset($view_mode)) {
            $this->view_mode = $view_mode;
        }
        if (isset($edit_mode)) {
            $this->edit_mode = $edit_mode;
        }
        if (isset($tab)) {
            $this->tab = $tab;
        }
    }

    /**
     * Run
     *
     * Description
     *
     * @access public
     */
    function run()
    {
        global $session;
        $out = array();
        if ($this->action == 'admin') {
            $this->admin($out);
        } else {
            $this->usual($out);
        }
        if (IsSet($this->owner->action)) {
            $out['PARENT_ACTION'] = $this->owner->action;
        }
        if (IsSet($this->owner->name)) {
            $out['PARENT_NAME'] = $this->owner->name;
        }
        $out['VIEW_MODE'] = $this->view_mode;
        $out['EDIT_MODE'] = $this->edit_mode;
        $out['MODE'] = $this->mode;
        $out['ACTION'] = $this->action;
        $out['TAB'] = $this->tab;
        $this->data = $out;
        $p = new parser(DIR_TEMPLATES . $this->name . "/" . $this->name . ".html", $this->data, $this);
        $this->result = $p->result;
    }

    /**
     * BackEnd
     *
     * Module backend
     *
     * @access public
     */
    function admin(&$out)
    {
        $this->getConfig();
        $out['API_URL'] = $this->config['API_URL'];
        if (!$out['API_URL']) {
            $out['API_URL'] = 'http://';
        }
        $out['API_KEY'] = $this->config['API_KEY'];
        $out['API_USERNAME'] = $this->config['API_USERNAME'];
        $out['API_PASSWORD'] = $this->config['API_PASSWORD'];
        if ($this->view_mode == 'update_settings') {
            global $api_url;
            $this->config['API_URL'] = $api_url;
            global $api_key;
            $this->config['API_KEY'] = $api_key;
            global $api_username;
            $this->config['API_USERNAME'] = $api_username;
            global $api_password;
            $this->config['API_PASSWORD'] = $api_password;
            $this->saveConfig();
            $this->redirect("?");
        }
        if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
            $out['SET_DATASOURCE'] = 1;
        }
        if ($this->data_source == 'app_okey_google' || $this->data_source == '') {
            if ($this->view_mode == '' || $this->view_mode == 'search_app_okey_google') {
                $this->search_app_okey_google($out);
            }
            if ($this->view_mode == 'edit_app_okey_google') {
                $this->edit_app_okey_google($out, $this->id);
            }
            if ($this->view_mode == 'delete_app_okey_google') {
                $this->delete_app_okey_google($this->id);
                $this->redirect("?");
            }
        }
    }

    /**
     * FrontEnd
     *
     * Module frontend
     *
     * @access public
     */
    function usual(&$out)
    {
        $this->admin($out);
    }

    /**
     * app_okey_google search
     *
     * @access public
     */
    function search_app_okey_google(&$out)
    {
        require(DIR_MODULES . $this->name . '/app_okey_google_search.inc.php');
    }

    /**
     * app_okey_google edit/add
     *
     * @access public
     */
    function edit_app_okey_google(&$out, $id)
    {
        require(DIR_MODULES . $this->name . '/app_okey_google_edit.inc.php');
    }

    /**
     * app_okey_google delete record
     *
     * @access public
     */
    function delete_app_okey_google($id)
    {
        $rec = SQLSelectOne("SELECT * FROM app_okey_google WHERE ID='$id'");
        // some action for related tables
        SQLExec("DELETE FROM app_okey_google WHERE ID='" . $rec['ID'] . "'");
    }

    function processSubscription($event, $details = '')
    {
        $this->getConfig();
        if ($event == 'SAY') {
            $level = $details['level'];
            $message = $details['message'];
            //...
        }
    }

    /**
     * Install
     *
     * Module installation routine
     *
     * @access private
     */
    function install($data = '')
    {
        subscribeToEvent($this->name, 'SAY');
        parent::install();
    }

    /**
     * Uninstall
     *
     * Module uninstall routine
     *
     * @access public
     */
    function uninstall()
    {
        SQLExec('DROP TABLE IF EXISTS app_okey_google');
        parent::uninstall();
    }

    /**
     * dbInstall
     *
     * Database installation routine
     *
     * @access private
     */
    function dbInstall($data)
    {
        /*
        app_okey_google -
        */
        $data = <<<EOD
 app_okey_google: ID int(10) unsigned NOT NULL auto_increment
 app_okey_google: TITLE varchar(100) NOT NULL DEFAULT ''
EOD;
        parent::dbInstall($data);
    }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgQXVnIDMxLCAyMDE4IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
