<?php

/**
 * Jelly View
 * 
 * @category  Jelly
 * @package   View
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/jelly
 */
Class Jelly_View_View
{
    /**
     * Form elements type
     * 
     * Types: ("input", "text", "dropdown", "textarea" ..)
     * 
     * @var string
     */
    public $type = '';

    /**
     * Form data
     * 
     * @var array
     */
    public $formData = array();

    /**
     * Form is ajax?
     * 
     * @var boolean
     */
    public $isAjax = false;
    
    /**
     * Elements is group?
     * This variable uses to 'repeatedDiv'.
     * 
     * 'tpl.groupedElementDiv' => array(
     *     'groupedDiv' => '<div class="form-group">
     *         <label class="col-sm-2 control-label">%s</label>
     *         %s
     *     </div>',
     *     'repeatedDiv' => '<div class="col-sm-2">%s</div>'
     * )
     * 
     * @var boolean
     */
    public $isGroup = false;

    /**
     * Elements is grouped?
     * This variable uses to 'groupedDiv'.
     * 
     * 'tpl.groupedElementDiv' => array(
     *     'groupedDiv' => '<div class="form-group">
     *         <label class="col-sm-2 control-label">%s</label>
     *         %s
     *     </div>',
     *     'repeatedDiv' => '<div class="col-sm-2">%s</div>'
     * )
     * 
     * @var boolean
     */
    public $isGrouped = false;

    /**
     * Temporary data to group elements.
     * 
     * @var string
     */
    public $groupElementsTemp = '';

    /**
     * Form elements extra data
     * Array
     * (
     *     [label] => Username
     *     [type] => input
     *     [func] => 
     *     [group] => 0
     * )
     * 
     * @var array
     */
    public $fieldExtraData = array();

    /**
     * Form elements data
     * 
     * Array
     * (
     *     [name] => username
     *     [title] => Username
     *     [rules] => required|min(3)|max(15)
     *     [attribute] =>  id="username"
     *     [type] => input
     *     [value] =>
     * )
     * 
     * @var array
     */
    public $fieldData = array();

    /**
     * 'parentDiv' => '<div class="form-group col-sm-%d">%s</div>'
     * 
     * @var integer
     */
    public $colSm = 3;

    /**
     * Form html
     * 
     * @var string
     */
    public $form = '';

    /**
     * Submit button
     * 
     * @var string
     */
    public $submit = '';

    /**
     * Form open tag
     * 
     * @var string
     */
    public $open = '';

    /**
     * Close tag
     * 
     * @var string
     */
    public $close = '';

    /**
     * Is group description
     * 
     * @var boolean
     */
    public $isGroupDescription = false;

    /**
     * Constructor
     * 
     * @param array $c container
     */
    public function __construct($c)
    {
        $this->c = $c;
        $this->formElement = $c->load('return form/element');
        $this->validator   = $c->load('return validator');
    }

    /**
     * Set submit
     * 
     * @param string $submit submit
     * 
     * @return void
     */
    public function setSubmit($submit)
    {
        $this->submit = $submit;
    }

    /**
     * Form is ajax?
     * 
     * @return boolean
     */
    public function isAjax()
    {
        return (boolean)$this->isAjax;
    }

    /**
     * Elements is group?
     * 
     * @return boolean
     */
    public function isGroup()
    {
        return (boolean)$this->isGroup;
    }

    /**
     * Elements is grouped?
     * 
     * @return boolean
     */
    public function isGrouped()
    {
        return (boolean)$this->isGrouped;
    }

    /**
     * Set elements group description.
     * 
     * @param boolean $isGroupDescription is group desc?
     * 
     * @return boolean
     */
    public function setGroupDescription($isGroupDescription)
    {
        $this->isGroupDescription = (boolean)$isGroupDescription;
    }

    /**
     * Elements is group description?
     * 
     * @return boolean
     */
    public function isGroupDescription()
    {
        return (boolean)$this->isGroupDescription;
    }

    /**
     * Label is translate?
     * 
     * @return boolean
     */
    public function isTranslatorEnabled()
    {
        if (class_exists('\\Obullo\Translation\Translator', false) === true) {
            return true;
        }
        return false;
    }

    /**
     * Get group elements temporary data.
     * 
     * @return string
     */
    public function getGroupElementsTemp()
    {
        return $this->groupElementsTemp;
    }

    /**
     * Get field data
     * 
     * @return array
     */
    public function getFieldData()
    {
        return $this->fieldData;
    }

    /**
     * Get field extra data
     * 
     * @return array
     */
    public function getFieldExtraData()
    {
        return $this->fieldExtraData;
    }

    /**
     * Get col-sm
     * 
     * @return integer
     */
    public function getColSm()
    {
        return $this->colSm;
    }

    /**
     * Form open tag
     * 
     * @return html
     */
    public function open()
    {
        return $this->open;
    }

    /**
     * Get submit
     * 
     * @param boolean $isTemplate is template?
     * 
     * @return string
     */
    public function submit($isTemplate = true)
    {
        if ($isTemplate) {
            return $this->c['jelly']->getElementDiv($this->submit);
        }
        return $this->submit;
    }

    /**
     * Get form values
     * 
     * @return array form data
     */
    public function getFormValues()
    {
        return $this->c['jelly']->getRawValues();
    }

    /**
     * Get after special group data.
     * 
     * @return array
     */
    public function getAfterGroup()
    {
        return $this->c['jelly']->getAfterGroup();
    }

    /**
     * Get append special group data.
     * 
     * @return array
     */
    public function getAppendGroup()
    {
        return $this->c['jelly']->getAppendGroup();
    }

    /**
     * Get col sm
     * 
     * @return array
     */
    public function getColSmArray()
    {
        return $this->c['jelly']->getColSm();
    }

    /**
     * Form close tag
     * 
     * @return html
     */
    public function close()
    {
        return $this->formElement->close();
    }

    /**
     * Render
     * 
     * @param array   $data      form data
     * @param boolean $isAllowed is allowed
     * 
     * @return string
     */
    public function render($data = array(), $isAllowed)
    {
        if ($isAllowed === false) {
            echo 'No permission or no data.';
            return;
        }
        $this->form = '';
        $attributes = ''; // reset attributes variable
        $colSmArray = $this->getColSmArray();

        foreach ($data['form'] as $k => $v) {
            if ($k !== Jelly_Form::FORM_ATTRIBUTE
                AND $k !== Jelly_Form::FORM_ACTION
                AND $k !== Jelly_Form::FORM_PRIMARY_KEY
                AND $k !== Jelly_Form::FORM_ID
            ) {
                $attributes .= $k .'="'. $v .'" ';
            }
        }
        $attributes    .= 'id="'. $data['form'][Jelly_Form::FORM_ID] . '"';
        $this->isAjax   = (isset($data['form']['ajax'])) ? $data['form']['ajax'] : 0;
        $this->formData = $data['form'];

        $this->open = $this->formElement->open($data['form'][Jelly_Form::FORM_ACTION], $attributes . $data['form'][Jelly_Form::FORM_ATTRIBUTE]);
        foreach ($data['elements'] as $val) {

            $this->fieldExtraData = $val['extra'];
            /**
             * $numberOfElements Total number of group elements
             * 
             * The "$numberOfElements" in form_groups
             * table database field : 'number_of_elements'.
             * 
             * @var integer
             */
            $numberOfElements = isset($this->fieldExtraData[Jelly_Form::GROUP_NUMBER_OF_ELEMENTS]) ? (int)$this->fieldExtraData[Jelly_Form::GROUP_NUMBER_OF_ELEMENTS] : 0;
            $groupID          = isset($this->fieldExtraData[Jelly_Form::GROUP_PRIMARY_KEY]) ? (int)$this->fieldExtraData[Jelly_Form::GROUP_PRIMARY_KEY] : 0;

            // resets
            $i = 0;
            $this->fieldData = array();
            $this->isGroup   = false;
            $this->isGrouped = false;
            $this->groupElementsTemp = '';
            foreach ($val['input'] as $v) {
                $i++;
                if (isset($v[Jelly_Form::ELEMENT_TYPE]) AND ! empty($v[Jelly_Form::ELEMENT_TYPE])) {

                    $attr = isset($v[Jelly_Form::ELEMENT_ATTRIBUTE]) ? $v[Jelly_Form::ELEMENT_ATTRIBUTE] : '';
                    $this->fieldData = $v;
                    $this->fieldData[Jelly_Form::ELEMENT_ATTRIBUTE] = $attr;

                    $ElementClassName = 'Jelly_View_Elements_'. ucfirst($v[Jelly_Form::ELEMENT_TYPE]); // E.g. Obullo\Jelly\View\Elements\Input
                    $this->element    = new $ElementClassName($this->c, $v[Jelly_Form::ELEMENT_NAME]);         // Initialized element class

                    if ($numberOfElements > 0) {        // If the input group
                        $this->isGroup = true;          // Set is group true

                        if ($v[Jelly_Form::ELEMENT_TYPE] != 'radio') {
                            if (isset($colSmArray[$v[Jelly_Form::ELEMENT_NAME]])) {
                                $this->colSm = $colSmArray[$v[Jelly_Form::ELEMENT_NAME]];
                            } else {
                                $this->colSm = ($numberOfElements === $i) ? ceil(3 / $numberOfElements) * 3 : 3;
                            }
                        }
                        $this->groupElementsTemp .= $this->element->render($this, $this->colSm);

                        $afterGroupData = $this->getAfterGroup();
                        $appendGroupData = $this->getAppendGroup();

                        if ($numberOfElements === $i) {  // $numberOfElements ve anahtar birbirine eşit ise
                                                         // biz bu inputların gruplama işleminin tamamladığını anlıyoruz.
                            $this->isGrouped = true;     // isGroup değişkenini "true" olarak set ederek render() 'ın
                                                         // ['tpl.groupedElementDiv']['groupedDiv'] kullanarak ana div içine almasını sağlıyoruz.

                            if (isset($appendGroupData[$groupID])) {
                                $this->groupElementsTemp .= $appendGroupData[$groupID];
                            }
                            $this->form .= $this->element->render($this);


                            if (isset($afterGroupData[$groupID])) {
                                $this->form .= $afterGroupData[$groupID];
                            }
                        }

                    } else {
                        $this->form .= $this->element->render($this);
                    }
                }
            }
        }
    }

    /**
     * Create hidden input
     * 
     * @param string $name element name
     * 
     * @return html hidden input
     */
    public function createHiddenInput($name)
    {
        return $this->formElement->hidden($name);
    }

    /**
     * Form
     * 
     * @return string form
     */
    public function form()
    {
        return $this->form;
    }
}

// END View Class
/* End of file View.php */

/* Location: .Obullo/Jelly/View.php */