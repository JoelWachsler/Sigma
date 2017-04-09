<?php

class UtilController extends BaseController {

    public function return_image($id)
    {
        // Got to find unique_id instead of just the normal id in the database
        try
        {
            $img = Image::findOrFail($id);
            // Check for additional parameters

            // Image found without parameters
            header("Content-type: image/".$img->type);
            echo $img->data;
        }
        catch(Exception $e)
        {
            // Image was not found
            // Returning custom image from the public folder
            $img_not_found_path = public_path('img/not-found.png');
            $file = new Symfony\Component\HttpFoundation\File\File($img_not_found_path);

            header("Content-type: image/".$file->getMimeType());
            return file_get_contents($file);
        }
    }

    public function upload_img()
    {
        // Check for valid input
        if (Input::hasFile('img'))
        {
            // Get file
            $file = Input::file('img');
            $rules = array('img' => 'mimes:jpg,jpeg,bmp,png');
            $data = array('img', Input::file('img'));
            // Create validator
            $validation = Validator::make($data, $rules);
            // Check file with validator
            if ($validation->fails())
            {
                // Wrong file
                return Response::json(array('error' => 'Wrong filetype'), 400);
            }

            // Multifile upload
            if (is_array($file))
            {
                // Not finished yet
                return Response::json(array('error' => 'not yet supported'), 400);
                //foreach($file as $part)
                //{
                //    $filename = $part->getClientOriginalName();
                //}
            }
            else
            {
                // Single file

                // Get the temporary path of the image
                $path = $file->getRealPath();
                // Get the extension of the image
                // for example .png or .jpg
                $type = $file->getClientOriginalExtension();
                // Get the actual content of the image
                $data = file_get_contents($path);
                
                // Create a new row in the databse
                $img = new Image;
                // Fill the row
                // Seed random number generator
                srand(time());
                $unique_id = base64_encode(rand(1, 1000000000));
                $img->user_id = Auth::user()->id;
                $img->unique_id = $unique_id;
                $img->data = $data;
                $img->type = $type;
                $img->save();

                // Profile picture upload
                if (Input::has('profile_pic'))
                    // Update users table with the new image id
                    return $this->profile_update_pic($unique_id);
                else

                // Upload was done correctly and we're returning the id of the image
                // for reference later when we're getting in from the databse
                return Response::json(array('success' => $unique_id), 200);
            }
        }
        else
        {
            // There was no file
            return Response::json(array('error' => 'No file input'), 400);
        }
    }

    /**
     *  @desc:      Replaces
     *
     **/
    private function profile_update_pic($id)
    {
        // Get the current user
        $user = Auth::user();
        // Assign new id for profile picture
        $user->img_id = $id;
        $user->save();

        // Return to the previous page with message
        return Redirect::route('settings')->with('msg', 'Profilbild uppdaterad!');
    }

    /**
     *  @desc:      Check if there are any alerts in the alert table
     *              with the id of the current user
     *  @return:    JSON_OBJ
     **/
    public function get_alerts()
    {
        // Get all rows in alert the user has not read
        $messages  = Alert::where('user_id', '=', Auth::user()->id)
            ->join('types', function($join)
                {
                    // Get type of alert
                    $join->on('alerts.type_id', '=', 'types.id');
                })
            ->orderBy('alerts.created_at', 'desc')
            ->get(array('alerts.id', 'alerts.message', 'alerts.link_to_alert', 'types.type_name'));

        // Count rows where the message is not read
        $unread = Alert::where('user_id', '=', Auth::user()->id)
            ->where('read', '=', false)
            ->count();

        $response = new stdClass(); // Data holder
        // Fill data holder
        $response->unread   = $unread;
        $response->messages = $messages;

        return Response::json($response);
    }

    /**
     *  @desc:      Update `read` [bool] in the `alert` table
     *  @return:    JSON_OBJ
     **/
    public function read_alert()
    {
        $alert = Alert::where('user_id', '=', Auth::user()->id)
            ->update(['read' => true]);

        return Response::json(true);
    }

    /**
     * Toggles starred for the current user_id and task_id
     **/
    public function toggle_starred()
    {
        $task_id = Input::get('task_id');
        $active = Input::get('active');
        
        $instance = Starred::firstOrNew(['user_id' => Auth::user()->id, 'task_id' => $task_id]);
        $instance->user_id = Auth::user()->id;
        $instance->task_id = $task_id;
        if ($active == 'true')
        {
            $instance->save();
            return Response::json(true);
        }
        else
        {
            $instance->delete();
            return Response::json(false);
        }
    }
}

