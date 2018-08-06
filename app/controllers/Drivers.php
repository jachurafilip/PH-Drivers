<?php

    class Drivers extends Controller
    {

        public function __construct()
        {
            if(!isLoggedIn())
            {
                redirect('users/login');
            }

            $this->driverModel = $this->model('Driver');
        }

        public function index()
        {
            //Get drivers

            $drivers = $this->driverModel->getDriversOfUser();
            $data =[
                'drivers' => $drivers
            ];
            $this->view('drivers/index', $data);
        }

        public function add()
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                //SANITIZE POST array
                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

                $data = [
                    'name' => trim($_POST['name']),
                    'user_id' => $_SESSION['user_id'],
                    'name_err' => ''
                    ];

                //Validate name

                if(empty($data['name']))
                {
                    $data['name_err'] = 'Please fill the field';
                }

                //Make sure no errors
                if(empty($data['name_err']))
                {
                    if($this->driverModel->addDriver($data))
                    {
                        flash('driver_message', 'Driver Added');
                        redirect('drivers');
                    }else
                    {
                        die('Something went wrong');
                    }

                } else
                {
                    //Load view with errors
                    $this ->view('drivers/add',$data);
                }

            } else
            {
                $data = ['name' => ''];
                $this->view('drivers/add', $data);
            }
        }
    }