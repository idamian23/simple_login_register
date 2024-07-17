<?php

class Users extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model("User");
    }

    public function index()
    {
        dd('INDEX OF USERS');
    }

    public function register()
    {

        //$data = ['name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->view('users/register');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // User's data
            $user = $_POST['user'];
            if ($user['email']) {
                $user['email'] = strtolower($user['email']);
            }

            //Start with an empty array
            $errors = [];

            //username
            if (empty($user['username'])) {
                $errors['username'] = 'Username is required';
            }

            if ($this->userModel->findUserByUsername($user['username'])) {
                $errors['username'] = 'Username already exists';
            }

            //email
            if (empty($user['email'])) {
                $errors['email'] = 'Email is required';
            }

            if (filter_var($user['email'], FILTER_VALIDATE_EMAIL) == false) {
                $errors['email'] = 'Invalid email format';
            }

            if ($this->userModel->findUserByEmail($user['email'])) {
                //search in database if email already registered
                //and if exists:
                $errors['email'] = 'Email already exists';
            }

            //password
            if (empty($user['password'])) {
                $errors['password'] = 'Password is required';
            }

            if (strlen($user['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }

            if ($user['password'] != $user['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match';
            }


            //if no errors proceed to register the account

            if (count($errors) == 0) {
                $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
                $this->userModel->createUser($user);

                $data = ['title' => 'You are logged in!'];

                $this->view('pages/index', $data);
            } else {
                $data = ['user' => $user, 'errors' => $errors];

                $this->view('users/register', $data);
            }


        }

    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            redirect('pages/index');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Display the login form
            $this->view('users/login');
            return;
        }

//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//            // Sanitize POST data
//            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
//
//            // Extract user data from POST
//            $user = $_POST['user'] ?? [];
//
//            // Initialize errors array
//            $errors = [];
//
//            // Validate username
//            if (empty($user['username'])) {
//                $errors['username'] = 'Username is required';
//            }
//
//            // Validate password
//            if (empty($user['password'])) {
//                $errors['password'] = 'Password is required';
//            }
//
//            dd($this->userModel->findUserByUsername($user['username']));
//
//            // Check if user exists in the database
//            if (!isset($errors['username']) && !$this->userModel->getUserByUsername($user['username'])) {
//                $errors['username'] = 'Something went wrong';
//            }
//
//
//            if ($this->userModel->login($_POST['user']['username'], $_POST['user']['password'])) {
//                $this->view('pages/index', ['title' => 'You are logged in!']);
//            } else {
//                $data = ['user' => $user, 'errors' => $errors];
//
//                $this->view('users/login', $data);
//            }
//
//        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Extract user data from POST
            $user = $_POST['user'] ?? [];

            // Initialize errors array
            $errors = [];

            // Validate username
            if (empty($user['username'])) {
                $errors['username'] = 'Username is required';
            }

            // Validate password
            if (empty($user['password'])) {
                $errors['password'] = 'Password is required';
            }

            // Check if user exists in the database
            if (!isset($errors['username']) && !$this->userModel->findUserByUsername($user['username'])) {
                $errors['username'] = 'Something went wrong';
            }

            // If there are no errors, attempt to log in the user
            if (empty($errors)) {
                $loggedInUser = $this->userModel->login($user['username'], $user['password']);

                if ($loggedInUser) {
                    // Create user session
                    $this->createUserSession($loggedInUser);
                    return;
                } else {
                    // Add login error
                    $errors['login'] = 'Invalid username or password';
                }
            }

            // If there are errors, pass them back to the view
            $data = ['user' => $user, 'errors' => $errors];
            $this->view('users/login', $data);
        }

    }


    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_email'] = $user->email;

        redirect('pages/index');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();

        redirect('users/login');
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }


}