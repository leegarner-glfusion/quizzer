<?php
/**
 * Class to handle checkbox quiz questions.
 *
 * @author      Lee Garner <lee@leegarner.com>
 * @copyright   Copyright (c) 2018 Lee Garner <lee@leegarner.com>
 * @package     quizzer
 * @version     v0.0.4
 * @license     http://opensource.org/licenses/gpl-2.0.php
 *              GNU Public License v2 or later
 * @filesource
 */
namespace Quizzer\Questions;

/**
 * Class for checkbox questions.
 * Multiple answers are allowed.
 */
class checkbox extends \Quizzer\Question
{
    /**
     * Create the input selection for one answer.
     * Does not display the text for the answer, only the input element.
     *
     * @param   integer $a_id   Answer ID
     * @return  string          HTML for input element
     */
    protected function makeSelection($a_id)
    {
        // Show the answer as disabled and checked if the answer has already
        // been submitted.
        if ($this->have_answer > 0) {
            $disabled = 'disabled="disabled"';
            $sel = $this->have_answer == $a_id ? 'checked="checked"' : '';
        } else {
            $disabled = '';
            $sel = '';
        }
        return '<input id="ans_id_' . $a_id . '" type="checkbox" name="a_id[]" value="' . $a_id . '" ' . $disabled . ' ' . $sel . '/>';
    }


    /**
     * Verify the supplied answer ID against the correct value
     *
     * @param   array   $submitted  Submitted answer IDs
     * @return  float       Numeric score
     */
    public function Verify($submitted)
    {
        $correct = 0;
        $possible = count($this->Answers);
        foreach ($this->Answers as $id=>$ans) {
            switch ($ans['correct']) {
            case 1:
                if (in_array($id, $submitted)) {
                    $correct++;
                }
                break;
            case 0:
                if (!in_array($id, $submitted)) {
                    $correct++;
                }
            }
        }
        if ($this->partial_credit) {
            return ($correct / $possible);
        } else {
            return ($correct == $possible) ? 1 : 0;
        }
    }


    /**
     * Get an array of correct answer IDs
     *
     * @return   array      Array of correct answer IDs
     */
    public function getCorrectAnswers()
    {
        $retval = array();
        foreach ($this->Answers as $a_id => $ans) {
            if ($ans['correct']) {
                $retval[] = $a_id;
            }
        }
        if (empty($retval)) {
            $retval = array(0);   // Failsafe, but should not happen
        }
        return $retval;
    }


    /**
     * Check if this question type allows partial credit.
     *
     * @return  boolean     True if partial credit is allowed
     */
    protected function allowPartial()
    {
        return true;
    }

}

?>