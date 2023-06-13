<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{

    // Show all listings
    public function index(){
        return view('listings.index', 
        [
            'listings'=>  Listing::latest()
            ->filter(request(['tag', 'search']))->paginate(6)       
         ]);
    }


    // Show single listing
    public function show(Listing $listing){
        return view('listings.show',[
            'listing'=> $listing    ]); 
    }
    // Show create form
    public function create(){
        return view('listings.create');
    }

     // Store Listing data
     public function store(Request $request){
       $formFields = $request->validate(
        [
        'title'=>'required',
        'company' => ['required', Rule::unique('listings', 'company')],
        'location'=> 'required',
        'website' => ['required', Rule::unique('listings', 'website')],
        'email' => ['required', 'email'],
        'tags'=> 'required',
        'description'=> 'required'
        ]);
        // check if image location is saved in the db
        if($request->hasFile('logo')){
            $formFields['logo']= $request->file('logo')->store('logos', 'public');
        }
        // Creating fields in the database
        Listing::create($formFields);
        // Session::flash('message', 'listing created');

    
        return redirect('/')->with('message', 'Listing created Successfully');
       
    }
}
