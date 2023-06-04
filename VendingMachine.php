<?php

class Article {
    public $name;
    public $code;
    public $price;
    public $quantity;

    public function __construct($name, $code, $quantity, $price)
    {
        $this->name = $name;
        $this->code = $code;
        $this->quantity = $quantity;
        $this->price = $price;
    }

}

class VendingMachine {

    private $articles;
    private $credit;
    private $balance;
    private $sales;
    private $timestamp;


    public function __construct(){
        $this->articles = [];
        $this->credit = 0;
        $this->balance = 0;
        $this->sales = [];
        $this->timestamp = time();
    }

    function addArticle($article){
        $this->articles[] =  $article;
    }

    function insert($amount){
        $this->credit += $amount;
    }

    function choose($code) {
        foreach ($this->articles as $article) {
            if ($article->code === $code) {
                if ($this->credit < $article->price) {
                    return "Not enough money!";
                }

                if ($article->quantity <= 0) {
                    return "Item {$article->name}: Out of stock!";
                }

                $this->credit -= $article->price;
                $this->balance += $article->price;
                $article->quantity--;

                $hour = date('H', $this->timestamp);

                if (!isset($this->sales[$hour])) {
                    $this->sales[$hour] = 0;
                }

                $this->sales[$hour] += $article->price;

                return "Vending {$article->name}";
            }
        }

        return "Invalid selection!";
    }

    public function getPeakHours() {
        arsort($this->sales);

        $peakHours = [];
        $count = 0;

        foreach ($this->sales as $hour => $revenue) {
            $peakHours[] = "Hour $hour generated a revenue of $revenue";
            $count++;

            if ($count >= 3) {
                break;
            }
        }

        return $peakHours;
    }

    public function setTime($time) {
        $this->timestamp = strtotime($time);
    }

    function getChange(){
        return $this->credit;
    }

    function getBalance(){
        return $this->balance;
    }

}

