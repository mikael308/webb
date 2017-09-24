<?php
namespace Web\Pages\Main\Widgets\Accordion;

require_once "./framework/data/Widget.php";

class Data extends \Web\Framework\Data\Widget
{

    private $accordions;

    public function __construct()
    {
        $this->accordions = array();
    }

    public function setAccordions(
        $accordions
    ) {
        $this->accordions = $accordions;
    }

    public function addAccordion(
        $label,
        $panel
    ) {
        $this->accordions["$label"] = $panel;
    }

    public function getAccordions()
    {
        return $this->accordions;
    }

}
