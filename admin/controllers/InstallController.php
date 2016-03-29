<?php

class InstallController extends CI_Controller
{

    protected $provider;
    protected $module;


    public function install($module)
    {
        require_once APPPATH .'/installers/'. ucfirst($module) .'/Installer.php';

        $this->module = $module;
        $this->provider = new \Installer();

        $sqlFile = '';
        if (file_exists(APPPATH .'/installers/'. ucfirst($module) .'/Database.sql')) {
            $sqlFile = $this->load->file(APPPATH .'/installers/'. ucfirst($module) .'/Database.sql', true);
        }

        if ($sqlFile) {
            $queries = explode(';', $sqlFile);

            foreach($queries as $query) {
                $query = trim($query);

                if (! empty($query)) {
                    $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
                    $this->db->query($query);
                    $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
                }
            }
        }

        $this->saveModule();
        $this->callMethods();
        $this->addRoutes();
        $this->addReservedUri();


    }


    private function saveModule()
    {
        $this->db->insert('modules', array(
            'title' => '',
            'name' => $this->module,
            'table' => '',
            'modified' => 0,
            'permissions' => '',
            'type' =>  null,
            'icon' =>  null,
            'menuPattern' => null,
            'controller' => '',
        ));
    }


    private function callMethods()
    {
        $methods = array();

        if (! empty($this->provider->steps)) {
            foreach ($this->provider->steps as $step) {
                if (method_exists($this->provider, $step)) {
                    $methods[] = $step;
                }
            }
        } else {
            foreach (get_class_methods($this->provider) as $step) {
                if (strpos($step, '__') === false) {
                    $methods[] = $step;
                }
            }
        }

        foreach ($methods as $method) {
            $this->provider->$method();
        }

    }


    private function addRoutes()
    {
        if (empty($this->provider->routes)) {
            return false;
        }


        $languages = $this->config->item('languages');

        foreach ($languages as $language => $label) {
            if (isset($this->provider->routes[$language]['route'])) {

                $filepath = '../application/config/routes.php';
                $file = fopen($filepath, FOPEN_WRITE_CREATE);
                $data = '';

                foreach ($this->provider->routes[$language]['route'] as $pattern => $action) {
                    $patterns = array();

                    // Aktif dil tr değilse prefix ekle.
                    if ($language !== 'tr') {
                        $patterns[] = $language;
                    }

                    // Aktif pattern'de @uri parametresi varsa uri değerini replace et.
                    if (isset($this->provider->routes[$language]['uri'])) {
                        $pattern = str_replace('@uri', $this->provider->routes[$language]['uri'], $pattern);
                    }

                    if (! empty($pattern)) {
                        $patterns[] = $pattern;
                    }

                    if (count($patterns) > 0) {
                        $data .= '$route[\''. implode('/', $patterns) .'\'] = \''. $action .'\';'. PHP_EOL;
                    }
                }

                flock($file, LOCK_EX);
                fwrite($file, $data);
                flock($file, LOCK_UN);
                fclose($file);

            }

        }

    }


    private function addReservedUri()
    {
        if (empty($this->provider->routes)) {
            return false;
        }

        $languages = $this->config->item('languages');

        foreach ($languages as $language => $label) {
            if (isset($this->provider->routes[$language]['uri'])) {

                $filepath = '../config/reservedUri.php';
                $file = fopen($filepath, FOPEN_WRITE_CREATE);
                $data = '$config[\'reservedUri\'][\''. $language .'\'][\'@'. $this->module .'\'] = \''. $this->provider->routes[$language]['uri'] .'\';'. PHP_EOL;

                flock($file, LOCK_EX);
                fwrite($file, $data);
                flock($file, LOCK_UN);
                fclose($file);

            }
        }

    }


} 