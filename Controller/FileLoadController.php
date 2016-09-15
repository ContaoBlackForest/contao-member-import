<?php

/**
 * Copyright © ContaoBlackForest
 *
 * @package   contao-member-import
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2014-2016 ContaoBlackForest
 */

namespace ContaoBlackForest\Member\Import\Controller;

use Contao\BackendTemplate;
use Contao\File;
use Contao\Input;
use Contao\Session;

/**
 * The file load controller.
 */
class FileLoadController
{
    /**
     * The import settings
     */
    protected $settings;

    /**
     * The data from import file.
     */
    protected $importData;

    /**
     * Handle the file loader.
     *
     * @return string The template.
     */
    public function handle()
    {
        if (Input::get('import') === 'analysis') {
            return '';
        }

        $this->getSession();

        $template = new BackendTemplate('be_member_import_load');


        $step = $this->collectData();

        $this->injectScript($step);

        $count = count($this->settings);

        $template->step  = $step;
        $template->count = $count;

        return $template->parse();
    }

    /**
     * Get the import settings from the session.
     *
     * @return void
     */
    protected function getSession()
    {
        $session = Session::getInstance();

        $this->settings   = $session->get('member-import-settings');
        $this->importData = $session->get('member-import-data');
    }

    protected function setSession()
    {
        $session = Session::getInstance();

        $session->set('member-import-data', $this->importData);
    }

    /**
     * Collect the data from import file.
     *
     * @return integer The step.
     */
    protected function collectData()
    {
        if (count($this->importData) === count($this->settings)) {
            return count($this->importData);
        }

        $step = Input::get('step');
        if (!$step) {
            $step = 0;
        }

        $setting = $this->settings[$step];
        $data    = $this->loadFile($setting['importSource']);

        $this->importData[] = array(
            'setting' => $setting,
            'data'    => $data
        );

        $this->setSession();

        return ++$step;
    }

    /**
     * Load the file source.
     *
     * @param $fileSource string The file source.
     *
     * @return array The file data.
     */
    protected function loadFile($fileSource)
    {
        $file = new File($fileSource, false);

        $header = array();
        $data   = array();
        foreach ($file->getContentAsArray() as $index => $line) {
            $csv = str_getcsv($line, ';');
            if ($index === 0) {
                $header = $csv;

                continue;
            }

            $data[] = $this->prepareData($header, $csv);
        }

        return $data;
    }

    /**
     * Prepare the data.
     *
     * @param $header  array The column names.
     *
     * @param $content array The columns.
     *
     * @return array The Data.
     */
    protected function prepareData($header, $content)
    {
        $data = array();

        foreach ($content as $line => $value) {
            $data[$header[$line]] = $value;
        }

        return $data;
    }

    /**
     * Inject the reload script.
     *
     * @param $step integer The step.
     *
     * @return void
     */
    protected function injectScript($step)
    {
        if (in_array(Input::get('import'), array('prepare', 'import'), null)) {
            return;
        }

        if (!array_key_exists($step, $this->settings)) {
            $GLOBALS['TL_MOOTOOLS'][] =
                "<script>location.href = 'contao/main.php" .
                "?do=member_import&import=prepare&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID .
                "'</script>";

            return;
        }

        $GLOBALS['TL_MOOTOOLS'][] =
            "<script>location.href = 'contao/main.php" .
            "?do=member_import&import=load&step=" . $step . "&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID .
            "'</script>";
    }
}
