<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str; // Add this import
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Response; // Add this import

use Illuminate\Support\Facades\Log;
class StudentController extends Controller

 {
    // public function index(){
    //      $stud = Students::all();
     
    //     return view('students.index',  ['students' => $stud]);
    // }
    
    public function index(){
         $data = array('students' => DB::table('students')->orderBy
         ('created_at', 'desc')->paginate(10));
         return view('students.index', $data); 
    }



    public function show($id){
        $data = Students::findorFail($id);
       
    
       return view('students.edit', ['students' => $data,
                                        'title' => 'Edit-student-data' ]);

    }

    public function add(){
        return view('students.add_students')->with('title', 'Add New');
    }


     public function update(Request $request, $id)
     {
         try {
             $validated = $request->validate([
                 'first_name' => ['required', 'min:3'],
                 'last_name' => ['required', 'min:4'],
                 'gender' => ['required', 'min:4'],
                 'age' => ['required', 'numeric', 'min:1'],
                 'password' => ['nullable', 'min:6'], // Make password field nullable
                 'email' => [
                     'required',
                     'email:rfc,dns',
                     Rule::unique('students', 'email')->ignore($id),
                 ],
             ], [
                 'email.email' => 'The email must be a valid email address.',
                 'email.email_rfc' => 'The email must comply with RFC standards.',
                 'email.email_dns' => 'The email domain must have valid DNS records.',
                 'email.unique' => 'The email already exists. Please choose a different email.',
                 'age.numeric' => 'The age must be a number.',
             ]);
     
             // Find the student by ID
             $student = Students::findOrFail($id);
     
             // Set default password if not provided
             $validated['password'] = $request->input('password', '12345');
     
             // Check if the new email is unique (ignoring the current record)
             if ($student->email !== $validated['email'] && Students::where('email', $validated['email'])->exists()) {
                 return redirect()->route('student.show', ['id' => $id])->with('email_error', 'Email already exists. Please choose a different email.');
             }
     
             // Update the student data
             $student->update($validated);
     
             // Redirect back to the student edit page with a success message
             return redirect()->route('student.show', ['id' => $id])->with('success', 'Student data updated successfully');
            } catch (\Exception $e) {
                // Log the exception for debugging purposes
                Log::error('Error updating student data: ' . $e->getMessage());
                Log::error($e->getTraceAsString()); // Log the stack trace
            
                // If an exception occurs, handle the error
                return redirect()->route('student.show', ['id' => $id])->with('error', 'Failed to update student data. Please try again.');
            }
     }
     
public function destroy(Request $request, $id){
  
    $students = Students::findOrFail($id);
    $students->delete();
    return redirect('/dashboard')->with('success', 'Data successfully deleted!' );
}
public function create(Request $request)
{
    $validated = $request->validate([
        'first_name' => ['required', 'min:3'],
        'last_name' => ['required', 'min:4'],
        'gender' => ['required', 'min:4'],
        'age' => ['required', 'numeric', 'min:1'],
        'email' => ['required', 'email', Rule::unique('students', 'email')],
        'password' => ['required', 'min:6'], // Set a minimum length for the password
        'email_verified_at' => now(), // Mark the email as verified.
        'remember_token' => Str::random(10), // Generate a random token.
    ]);

    // Set the default password to '12345'
    $validated['password'] = bcrypt('12345');

    Students::create($validated);

    return redirect('/dashboard')->with('success', 'New student added successfully');
}
} 



/* to get all information in a table we use
 $data = Students::all(); 
 
 To Get A particular  data we use the WHERE command 
 and get() function  $data = Students::where('id', 1) ->get();


command to filter items in a data we use 'like' and what to
 filter like the '%bert%'

to compare 
  $data = Students::where('age', '>=', 22) ->get();
 
to Limit a number of display
    $data = Students::where('age', '<=', 22) ->
        orderBy('first_name', 'asc')->limit(5)->get();
    // desc stands for descending other
        //asc stands for ascending order

        How to return a 404 Error if get item is not found, than getting a blank Page, we use
                $data = Students::where('id', 100)->firstorfail()->get();
                Students::findorFail($id);

                USING WILDCARD TO FILTER
                   public function showid($id){
        $data = Students::findorFail($id);
        dd($data);
        return view('students.index', ['students' => $data]);
    }



    for gender count and sorting
        public function create(){
        $data =DB::table('students')
        ->select(DB::raw('count(*) as gender_count, gender'
        ))->groupBy('gender')->get();
     
        // $data = Students::where('id', 100)->firstorfail()->get();
        return view('students.index', ['students' => $data] );
    }
                */


                