<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function preValue($id)
    {
        if($id == 1) {
            $price = 600;
        } else if($id == 2) {
            $price = 600;
        } else if($id == 3 || $id == 4 || $id == 5 || $id == 6 || $id == 7 || $id == 8) {
            $price = 700;
        } else if($id == 9 || $id == 10 || $id == 11 || $id == 12 || $id == 13 || $id == 14 || $id == 15 || $id == 16) {
            $price = 800;
        } else if($id == 17 || $id == 18 || $id == 19 || $id == 20 || $id == 21 || $id == 22 || $id == 23) {
            $price = 900;
        } else if($id == 24 || $id == 25 || $id == 26 || $id == 27 || $id == 28 || $id == 29 || $id == 30) {
            $price = 1000;
        } else if($id == 31 || $id == 32 || $id == 33) {
            $price = 1100;
        } else if($id == 34 || $id == 35 || $id == 36 || $id == 37) {
            $price = 1300;
        } else if($id == 38) {
            $price = 1400;
        } else if($id == 39) {
            $price = 1500;
        } else if($id == 40) {
            $price = 1600;
        } else if($id == 41 || $id == 42) {
            $price = 1700;
        } else if($id == 43 || $id == 44) {
            $price = 1900;
        } else if($id == 45) {
            $price = 2600;
        } else if($id == 46) {
            $price = 3100;
        } else {
            $price = 0;
        }

        return $price;
    }
}
