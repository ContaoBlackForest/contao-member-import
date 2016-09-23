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
use Contao\Controller;
use Contao\Database;
use Contao\DC_Table;
use Contao\Input;
use Contao\Session;

/**
 * The import data controller
 */
class ImportDataController
{
    /**
     * The prepared data.
     */
    protected $preparedData;

    /**
     * Handle import the data to the database.
     *
     * @return string The template.
     */
    public function handle()
    {
        if (in_array(Input::get('import'), array('analysis', 'load', 'prepare'), null)) {
            return '';
        }

        $template = new BackendTemplate('be_member_import_import');

        $this->getSession();

        $step  = $this->importData();
        $count = count($this->preparedData);

        $this->injectScript($step);

        $template->step  = $step;
        $template->count = $count;

        return $template->parse();
    }

    /**
     * Import the data to the database.
     *
     * @return integer The step.
     */
    protected function importData()
    {
        $step = Input::get('step');
        if (!$step) {
            $step = 0;
        }

        if (count($this->preparedData) === (int) $step) {
            return $step;
        }

        $importData = $this->verifyData($this->preparedData[$step]);

        $this->save($importData);

        return ++$step;
    }

    /**
     * Verify the import data.
     *
     * @param $data array The data for verify.
     *
     * @return array The verify data.
     */
    protected function verifyData($data)
    {
        $database = Database::getInstance();

        $allowedProperties = $database->getFieldNames('tl_member');

        foreach ($data as $property => $value) {
            if (in_array($property, $allowedProperties, null)) {
                continue;
            }

            unset($data[$property]);
        }

        return $data;
    }

    /**
     * Save the data to the database.
     *
     * @param $data array The data to save.
     *
     * @return void
     */
    protected function save($data)
    {
        Controller::loadDataContainer('tl_member');

        $dcTable = new DC_Table('tl_member');

        $database = Database::getInstance();
        $result   = $database->prepare('SELECT * FROM tl_member WHERE email=?')
            ->execute($data['email']);

        $savedData = false;
        foreach ($data as $property => $value) {
            if (is_array($value)) {
                $value = serialize($value);
            }

            if ($result->{$property} != $value && $property !== 'password') {
                $savedData = false;

                continue;
            }

            $savedData = true;
        }

        if ($savedData) {
            return;
        }

        $exludedProperties = $database->getFieldNames('tl_member');
        foreach ($exludedProperties as $excludedProperty) {
            $GLOBALS['TL_DCA']['tl_member']['fields'][$excludedProperty]['exclude'] = false;
        }

        Input::setPost('FORM_SUBMIT', 'tl_member');
        Input::setPost('FORM_FIELDS', $this->prepareFormFields($result, $dcTable));

        if ($result->count() === 0) {
            $dcTable->create(array('email' => $data['email']));
        }

        foreach ($data as $property => $value) {
            if ($property === 'password') {
                Input::setPost('password_confirm', $value);
            }

            Input::setPost($property, $value);
        }

        if ($result->count() === 1) {
            $dcTable->edit($result->id);
        }
    }

    /**
     * Get the import settings from the session.
     *
     * @return void
     */
    protected function getSession()
    {
        $session = Session::getInstance();

        $this->preparedData = $session->get('member-import-prepare');
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
        if (in_array(Input::get('import'), array('analysis', 'load', 'prepare'))) {
            return;
        }

        if (count($this->preparedData) == Input::get('step')) {
            return;
        }

        $GLOBALS['TL_MOOTOOLS'][] =
            "<script>location.href = 'contao/main.php" .
            "?do=member_import&import=import&step=" . $step . "&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID .
            "'</script>";
    }

    /**
     * Prepare form fields form dc table.
     *
     * @param Database\Result $result        The member result.
     *
     * @param DC_Table        $dataContainer The data container.
     *
     * @return array The form fields.
     */
    protected function prepareFormFields($result, DC_Table $dataContainer)
    {
        if ($result->id) {
            $dataContainer->id = $result->id;
        }

        $formFields = $dataContainer->getPalette();

        return (array) $formFields;
    }
}
