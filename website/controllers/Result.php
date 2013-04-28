<?php
class Result extends \Tachyon\Controller
{
    public function get() {
        $this->postcode = $this->getData("postcode", false);
        if($this->postcode) {
            $scoreFinder = (new \AreaScore\Score)->setMySQL(\AreaScore\MySQL::getInstance()->getConn());
            $score = $scoreFinder->getScore($this->postcode);

            $this->areaInfo = $scoreFinder->getAreaInfo();
            $this->areaScore = $score['areaScore'];
            $scoreBreakdown = $score['breakdown'];
            unset($scoreBreakdown['happiness']);
            unset($scoreBreakdown['age']);
            unset($scoreBreakdown['population']);
            $values = array_values($scoreBreakdown);
            array_walk($values, function(&$val) {
                $val = round($val/10, 1);
            });
            $this->spider = $values;

            $this->render("results.tpl");
        } else {
            $this->redirect("/");
        }
        $this->sendResponse();
    }
}
