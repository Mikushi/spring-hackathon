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
            $this->scoreBreakdown = $score['breakdown'];
            $this->render("results.tpl");
        } else {
            $this->redirect("/");
        }
        $this->sendResponse();
    }
}
