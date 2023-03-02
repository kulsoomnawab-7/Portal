<?php

namespace App\Http\Controllers;
use App\Models\lab;
use App\Models\user;
use App\Models\usermodels;
use App\Models\lab_system;
use App\Models\complain__master;
use DB;                                                                                             
use Illuminate\Http\Request;

class studentcontroller extends Controller
{
    public function registerget()
    {
        return view ("register");
    }

   
    public function loginadminpost(Request $req)
    {
        $email =$req->emailinput;
        $password= $req->passwordinput;

        $login =DB::table("usermodels")->where(["email"=>$email , "password"=>$password])->first();

        if($login!="")
        {
            session(["sessionid"=>$login->id]);
            session(["sessionusername"=>$login->name]);
            session(["sessionuseremail"=>$login->email]);     
            session()->has('Login_post') ;
            $student_data = DB::table('examsubjectmasters')->where('Curr_ID' , session('Login_post'))->orderBy('id','desc')->limit('1')->get();
            return view('student_dashboard',compact('student_data'));
          
        }

        else
        {
            return redirect()->back()->with("errormessage" , "Record Not Found");

        }
    }
    public function adminget()
    {
        return view("/login");
    }
   
    public function registerpost(Request $res)
    {
        $email =$res->emailinput;
        $studcheck =DB::table("students")->where("Student_email", $email)->first();
        // echo "nj".$studcheck;
        $pass =$res->passwordinput;

        if(strlen($pass) < 8)
        {
            echo "<script>alert('Woops! Password cannot be less the 8 characters.')
            window.location.href=''
            </script>";
            return;
            
        }
            
        else
        {
            if(isset($studcheck))
        {

        try{
            
        $user =DB::table("usermodels")->where("email", $email)->first();

            if(isset($user))
            {
                echo "<script>alert('Email Already Exists.')
                window.location.href='/register'
                </script>";
                // return redirect()->back()->with("success" , "Data has been inserted");

            }
            else{

                $pass =$res->passwordinput;
                $conpass =$res->coninput;
 
                if($pass == $conpass)
                {
                    $user = new usermodels();
                    $user->name = $res->nameinput;
                    $user->email = $res->emailinput;
                    $user->password = $res->passwordinput;
                    $user->save();
                }
                else{
                    // echo "<script>alert('Password Not Matched.')</script>";
                    echo "<script>alert('Password Not Matched.')
                    window.location.href=''
                    </script>";

                }
                
            }
        }
        catch(Exception $ex){


            echo $ex->getMessage();
            die;
        }
            echo "<script>alert('User Registration Completed.')
            window.location.href='/login'
            </script>";

        }

        }
    }

    public function dashboard_(Request $req)
    {
        // $studcheck =DB::table("students")->where("Student_email", $req->Auth::user()->email)->first();
        // echo  $studcheck;
        // return view("student_dashboard");
    }

    public function labs()
    {
        return view("labs");
    }
    public function lab_systems_()
    {
        return view("lab_systems");
    }

    public function lab()
    {
        $lab = new lab();
        $lab->No_of_pcs=$req->No_of_pcsinput;
        $lab->save();
        return redirect()->back();
    }

    public function lab_systems()
    {
        $lab = new lab_system();
        $lab->Host_Name=$req->Host_Nameinput;
        $lab->Status=$req->Statusinput;
        $lab->Lab_id=$req->Host_Nameinput;
        $lab->save();
        return redirect()->back();
    }
    public function exam_fetch(Request $req)
    {
        
    if(session()->get('Login_post')){
        $exam = DB::table('examsubjectmasters')->where('Curr_ID' , session()->get('Login_post'))->orderBy('id','asc')->get();
        $exam = DB::table('examsubjectmasters')->where('Curr_ID' , session()->get('Login_post'))->orderBy('id','asc')->get();
        return view('examfetch',compact('exam'));}
        
    }

    
    
}