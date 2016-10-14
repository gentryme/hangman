<?php
class Hangman{
    private $word;
    private $wrongAttempts;

    //running game temlate variables
    // private $greeting;
    // private $blanksShow;
    // private $linksList;
    // private $response;
    // private $usedList;
    // private $used_header;

    //new game template variables
    private $link;
    private $pt1;
    private $pt2;

    //arrays
    private $usedLetters = [];
    private $place_holder = [];
    private $letterLinks = [];

    //constants
    const PLACEHOLDER = "_";

    //constructor
    function __construct($word, $wrongAttempts, $usedLetters, $place_holder, $letterLinks){

        $this->word = $word;
        $this->wrongAttempts = $wrongAttempts;
        $this->usedLetters = $usedLetters;
        $this->place_holder = $place_holder;
        $this->letterLinks = $letterLinks;

    }

    //initial rendering info when starting a new game
    function initital(){
        // $this->greeting = 'Welcome to Hangman';
        // $this->blanksShow = implode(" ", $this->blanks());
        // $this->linksList = implode(" ", $this->alphabetList());
        // $this->response = 'Choose a letter from the list below...';

        $this->render("Welcome to Hangman", implode(" ", $this->blanks()),
        "Choose a letter from the list below...", implode(" ", $this->alphabetList()));
    }

    //actually runs the game
    function run_game(){
        $this->pt1 = 'Wanna start a new game? Click here! >>> ';
        $this->link = "LET'S DO THIS";
        $this->pt2 = ' <<<';

        $letter = $_REQUEST['letter'];
        // if(strcasecmp(implode("", $this->checkWord()), $this->word) !== 0 && $this->wrongAttempts === 6){
            $this->usedLetters[] = $letter;
            $this->wrongAttempts;

            $this->render("You lost the game!", implode(" ", $this->checkWord()),
            "The hidden word is shown above was: $this->word",
            null,"Here are the letters you tried:", implode(" ", $this->usedLetters), "Wanna play again? Click here! >>>",
            "LET'S DO THIS", " <<<");
        }

        // else if(stripos($this->word, $letter) !== false && $this->wrongAttempts < 6
        // && strcasecmp(implode("", $this->checkWord()), $this->word) !== 0){
            else{
                $this->usedLetters[] = $letter;

                $this->render("The letter you chose was found in the word!", implode(" ", $this->checkWord()),
                "Choose another letter from the list below...", implode(" ", $this->destroyLink()),
                "Here are the letters you tried:", implode(" ", $this->usedLetters), $this->pt1,
                $this->link, $this->pt2);

            }
        }

        else if($this->wrongAttempts < 6 && strcasecmp(implode("", $this->checkWord()), $this->word) !== 0){
            $this->usedLetters[] = $letter;
            $this->wrongAttempts += 1;

            if($this->wrongAttempts === 6){
                $this->render("You lost the game!", implode(" ", $this->checkWord()),
                "The hidden word is shown above was: $this->word",
                null,"Here are the letters you tried:", implode(" ", $this->usedLetters), "Wanna play again? Click here! >>>",
                "LET'S DO THIS", " <<<");
            }
            else{
                $this->render("The letter you chose was not found. Try again!", implode(" ", $this->checkWord()),
                "Choose another letter from the list below...", implode(" ", $this->destroyLink()),
                "Here are the letters you tried:", implode(" ", $this->usedLetters), $this->pt1,
                $this->link, $this->pt2);
            }
        }

        else{
            $this->usedLetters[] = $letter;

            $this->render("You won!", implode(" ", $this->checkWord()), "The hidden word is shown above.", null,
            "Here are the letters you tried:", implode(" ", $this->usedLetters), "Wanna play again? Click here! >>>",
            "LET'S DO THIS", " <<<");
        }
    }

    //fxn to create alphabet links
    function alphabetList(){
        for ($i = 65; $i <= 90; $i++) {
            $this->letterLinks[] = '<a href="/phpwebsite/index.php?module=hangman&letter='.chr($i).'">'.chr($i).'</a>';
        }
        $_SESSION['letter'] = $this->letterLinks;
        return $this->letterLinks;
    }

    //fxn for display of INITIAL placeholder
    function blanks(){
        for($i = 0; $i < strlen($this->word); $i++){
            $this->place_holder[] = self::PLACEHOLDER;
        }
        return $this->place_holder;
    }

    //function to compare letter to word and print respective placeholder
    function checkWord(){

        $len = strlen($this->word);
        $letter = $_REQUEST['letter'];
        $pos = stripos($this->word, $letter);

        while($pos < ($len - 1) && $pos !== false){
            $this->place_holder[$pos] = $letter;
            $pos = stripos($this->word, $letter, $pos + 1);
        }

        if($pos == ($len - 1)){
            $this->place_holder[$pos] = $letter;
        }

        return $this->place_holder;
    }

    //function to check conditions and return state of game
    function checkGame(){
        $check = implode("", $this->checkWord());

        if(strcasecmp($checked, $this->word) !== 0){
            if($this->wrongAttempts === 6){
                return "lost";
            }
            else if(stripos($this->word, $letter) !== false && $this->wrongAttempts < 6){
                return "found";
            }
        }
    }

    //deleting links as they're chosen and recreate alphabet links list
    function destroyLink(){
        $key = array_search('<a href="/phpwebsite/index.php?module=hangman&letter='.$_REQUEST['letter'].'">'.$_REQUEST['letter'].'</a>',
        $this->letterLinks);
        $this->letterLinks[$key] = null;
        $_SESSION['letter'] = $this->letterLinks;
        return $this->letterLinks;
    }

    //function to return placeholder
    function getPlaces(){
        return $this->place_holder;
    }

    //function to return word
    function getWord(){
        return $this->word;
    }

    //function to return wrong attempts
    function getWrongAttempts(){
        return $this->wrongAttempts;
    }

    //function to return used letterLinks
    function getUsedLetters(){
        return $this->usedLetters;
    }

    //function to render template vars
    function render($greeting, $blanksShow, $response, $linksList, $used_header = null, $usedList = null, $pt1 = null, $link = null,
    $pt2 = null){
        $template['GREETING'] = $greeting;
        $template['RESPONSE'] = $response;
        $template['IMG_SRC'] =
        "http://localhost/phpwebsite/mod/hangman/img/hang$this->wrongAttempts.gif";
        $template['BLANKS_WORD'] = $blanksShow;
        $template['ALPHABET'] = $linksList;
        $template['USED'] = $usedList;
        $template['USED_HEADER'] = $used_header;
        $template['LINK'] = $link;
        $template['PT1'] = $pt1;
        $template['PT2'] = $pt2;

        echo PHPWS_Template::process($template, 'hangman','game.tpl');
    }
}
