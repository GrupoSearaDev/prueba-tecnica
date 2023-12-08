<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;
use App\Validation\LoginRules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        LoginRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $rulesToCreateUser = [
        'name' => 'required|max_length[150]',
        'lastname' => 'required|max_length[150]',
        'phone' => 'required|numeric|exact_length[10]',
        'email' => 'required|max_length[100]|valid_email|is_unique[user.email]',
        'photo' => 'required|max_length[200]|valid_url',
        'password' => 'required|max_length[255]',
        'type'=> 'required|in_list[Administrador,Basico]',
    ];


    public array $rulesToUpdateUser = [
        'name' => 'if_exist|max_length[150]',
        'lastname' => 'if_exist|max_length[150]',
        'phone' => 'if_exist|numeric|exact_length[10]',
        'email' => 'if_exist|max_length[100]|valid_email|is_unique[user.email,email,$email]',
        'photo' => 'if_exist|max_length[200]|valid_url',
        'password' => 'if_exist|max_length[255]',
        'type'=> 'if_exist|in_list[Administrador,Basico]',
    ];

    public array $rulesToLogin = [
        'email' => [
            'rules'=>'required|max_length[100]|valid_email|',
            'errors'=>[
                'required'=>'email es required',
                'max_length'=>'email only accept max 100 caracters',
                'valid_email'=>'email is not valid'
            ]
        ],
        'password' => [
            'rules'=>'required|max_length[255]|validateUser[email,password]',
            'errors'=>[
                'required' => 'password es required',
                'max_length'=>'password only accept max 255 caracters',
                'validateUser'=>'Invalid login credentials provided'
            ]
        ]
    ];
}
