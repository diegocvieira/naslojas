<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;
use Mail;
use App\Mail\SendGenerateTokens;

class GenerateTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generate-tokens {quantity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tokens = [];

        for ($index = 1; $index <= $this->argument('quantity'); $index++) {
            $token = $this->generate();

            Token::create([
                'token' => $token
            ]);

            $tokens[] = $token;
        }

        Mail::to('vieiracdiego@gmail.com', 'Diego Vieira')
            ->send(new SendGenerateTokens($tokens));
    }

    public function generate()
    {
        $charsStart = 0;
        $charsLength = 16;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $token = substr(str_shuffle($chars), $charsStart, $charsLength);

        return $token;
    }
}
