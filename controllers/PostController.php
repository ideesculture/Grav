<?php
/* ----------------------------------------------------------------------
 * controllers/CollectionController.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014-2015 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */

require_once(__CA_LIB_DIR__."/core/ApplicationError.php");
require_once(__CA_LIB_DIR__."/ca/BasePluginController.php");
require_once(__CA_APP_DIR__.'/helpers/accessHelpers.php');
require_once(__CA_LIB_DIR__.'/ca/Search/CollectionSearch.php');
require_once(__CA_MODELS_DIR__.'/ca_collections.php');

$ps_plugin_path = __CA_APP_DIR__."/plugins/CMS";

class PostController extends BasePluginController {
    # -------------------------------------------------------

    # -------------------------------------------------------
    public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
        parent::__construct($po_request, $po_response, $pa_view_paths);

        if ($this->request->config->get('pawtucket_requires_login')&&!($this->request->isLoggedIn())) {
            $this->response->setRedirect(caNavUrl($this->request, "", "LoginReg", "LoginForm"));
        }

        $ps_plugin_path = __CA_APP_DIR__."/plugins/CMS";
        $this->opo_config = Configuration::load($ps_plugin_path.'/conf/CMS.conf');

        caSetPageCSSClasses(array("grav"));
    }
    # -------------------------------------------------------
    /**
     *
     */
    public function Index() {
        $this->redirect(__CA_URL_ROOT__);
    }

    public function _() {
        $user = $this->opo_config->get("user");
        $password = $this->opo_config->get("password");
        $hostname = $this->opo_config->get("hostname");
        $protocol = $this->opo_config->get("protocol");
        $vs_extra = $this->getRequest()->getActionExtra();

        $json = @file_get_contents($protocol."://".$user.":".$password."@".$hostname."/api/pages/".$vs_extra);
        if($json === false) {
            $this->render("404_html.php");
        } else {
            $page = json_decode($json, TRUE);

            $page_content = str_replace("<img src=\"/", "<img src=\"".$protocol."://".$hostname."/", $page["content"]);

            // Extracting the first image that would be used as a thumbnail
            preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $page_content, $img);
            $va_page_info = array();
            $va_page_info["title"] = $page["title"];
            $va_page_info["date"] = date("j/m/Y", $page["date"]);
            $va_page_info["content"] = $page_content;

            $this->view->setVar('page', $va_page_info);

            $this->render("page_html.php");

        }
    }


    # ------------------------------------------------------
}