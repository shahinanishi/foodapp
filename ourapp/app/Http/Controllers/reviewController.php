<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class reviewController extends Controller
{    
    public function showReviewForm() {
        return view('write-review');
    }

   /* public function saveReview(Request $request) {
        $incomingFields = $request->validate([
            'restaurantName' => 'required',
            'location' => 'required',
            'detailedReview' => 'required'
        ]);          
        $incomingFields['restaurantName'] = strip_tags($incomingFields['restaurantName']);
        $incomingFields['location'] = strip_tags($incomingFields['location']);
        $incomingFields['detailedReview'] = strip_tags($incomingFields['detailedReview']);
        $incomingFields['user_id'] = auth()->id();
        $newReview = Review::create($incomingFields);
        return redirect("/review/{$newReview->id}")->with('success','New Review posted!');
    }
    */

    public function viewSingleReview(Review $review){
        $review['detailedReview'] = Str::markdown($review->detailedReview);        
        return  view('single-review', ['review' => $review]);
    }

    public function delete(Review $review){
        $review->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success','Post successfully deleted!');
    }

    public function showEditForm(Review $review) {
        return view('edit-review',['review' => $review]);
    }

    /*public function actuallyUpdate(Review $review, Request $request) {
        $incomingFields = $request->validate([
            'restaurantName' => 'required',
            'location' => 'required',
            'detailedReview' => 'required'
        ]);          
        $incomingFields['restaurantName'] = strip_tags($incomingFields['restaurantName']);
        $incomingFields['location'] = strip_tags($incomingFields['location']);
        $incomingFields['detailedReview'] = strip_tags($incomingFields['detailedReview']);
        
        $review->update($incomingFields);
        return back()->with('success', 'Post successfully updated!');
    }
    
    public function showImageForm() {
        return view('image-form');
    }
    */

    
    
    /*public function storeImage(Request $request, Review $review){ 
        //You need to change the path of the storage shortcut in the top level public folder for it to work. run 'php artisan storage:link' to do so
        $request->validate([
            'image'=>'required|image|max:8000'
        ]);
        $imagename = $review->id.'-'.uniqid().'.jpg';

        #$request->file('image')->store('public/images');
        $img=Image::make($request->file('image'))->fit(640)->encode('jpg');
        
        Storage::put('public/images/'.$imagename,$img); 
        
        $review = new Review();
     $review->restaurantName = 'Sample Restaurant'; // Set a default value for restaurantName
     $review->location = 'Sample Location'; // Set a default value for location
     $review->detailedReview = 'Sample Review'; // Set a default value for detailedReview
     $review->user_id = auth()->user()->id; // Set the user ID
     $review->image = $imagename; // Set the image name
     $review->save(); // Save the new Review instance
        return back()->with('success','Image Uploaded');
    } 
    */

    public function saveReview(Request $request) {
        $incomingFields = $request->validate([
            'restaurantName' => 'required',
            'location' => 'required',
            'detailedReview' => 'required',
            'image' => 'required|image|max:8000' // Add image validation rule
        ]);
    
        $incomingFields['restaurantName'] = strip_tags($incomingFields['restaurantName']);
        $incomingFields['location'] = strip_tags($incomingFields['location']);
        $incomingFields['detailedReview'] = strip_tags($incomingFields['detailedReview']);
        $incomingFields['user_id'] = auth()->id();
    
        // Handle image upload
        $imagename = $incomingFields['user_id'] . '-' . uniqid() . '.jpg';
        $img = Image::make($request->file('image'))->fit(640)->encode('jpg');
        Storage::disk('public')->put('images/'.$imagename, $img);
        $incomingFields['image'] = $imagename;
    
        // Create a new Review instance with all fields and save
        $newReview = Review::create($incomingFields);
    
        return redirect("/review/{$newReview->id}")->with('success', 'New Review posted!');
    }
    

    public function search($term){
        $reviews = Review::search($term)->get();
        $reviews->load('user:id,username,avatar');
        return $reviews;
    }


}