<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertiseStoreRequest;
use App\Http\Requests\AdvertiseUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Advertise;
use App\Models\SectionAdvertise;
use App\Traits\AdvertiseTrait;
use Illuminate\Support\Facades\Auth;

class AdvertiseController extends Controller
{
    use AdvertiseTrait;

    public function advertises()
    {
        $userId = Auth::id();
        $advertise = Advertise::where('user_id', $userId)->latest()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Advertise list get successfully',
            'data' => $advertise
        ], 200);
    }
    public function store(AdvertiseStoreRequest $request)
    {
        $userId = Auth::id();

        $image = $request->file('image');

        $name = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/images/advertises');

        $image->move($destinationPath, $name);

        $advertise = new advertise;
        $advertise->user_id = $userId;
        $advertise->title = $request->title;
        $advertise->description = $request->description;
        $advertise->image = $name;
        $advertise->link = $request->link;
        $advertise->status = $request->status;
        $advertise->save();

        if ($request->section_id) {
            $this->advertiseAssignTosection($advertise, $request->section_id);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Advertise data has been saved'
        ], 200);
    }
    public function get_single_advertise(Advertise $advertise)
    {
        $advertise = $advertise->load('section_advertise.section');

        return Response::json([
            'status' => '200',
            'message' => 'Advertise data get successfully',
            'data' => $advertise
        ], 200);
    }
    public function update(AdvertiseUpdateRequest $request, Advertise $advertise)
    {
        $userId = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/images/advertises');

            $image->move($destinationPath, $name);
        }

        $advertise->user_id = $userId;
        $advertise->title = $request->title;
        $advertise->description = $request->description;
        if ($request->hasFile('image')) {
            $advertise->image = $name;
        }
        $advertise->link = $request->link;
        $advertise->status = $request->status;
        $advertise->save();

        if ($request->section_id) {
            $this->advertiseAssignTosection($advertise, $request->section_id);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Advertise data has been updated'
        ], 200);
    }
    public function delete(Advertise $advertise)
    {
        SectionAdvertise::where('advertise_id', $advertise->id)->delete();
        $advertise->delete();
        return Response::json([
            'status' => '200',
            'message' => 'Advertise move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_advertise()
    {
        $userId = Auth::id();
        $advertise = Advertise::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash Advertise list get successfully',
            'data' => $advertise
        ], 200);
    }
    public function trash_advertise_restore($advertise)
    {
        $userId = Auth::id();
        $advertise = Advertise::where('user_id', $userId)->onlyTrashed()->findOrFail($advertise);
        $advertise->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Advertise restored successfully'
        ], 200);
    }
    public function trash_advertise_delete($advertise)
    {
        $userId = Auth::id();
        $advertise = Advertise::where('user_id', $userId)->onlyTrashed()->findOrFail($advertise);
        $advertise->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash Advertise deleted successfully'
        ], 200);
    }
    public function all_trash_advertise_delete()
    {
        $userId = Auth::id();
        Advertise::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Advertises deleted successfully'
        ], 200);
    }

    public function remove_advertise_section(SectionAdvertise $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Advertise has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $userId = Auth::id();

        $assignedIds = SectionAdvertise::where('user_id', $userId)->pluck('advertise_id')->unique()->values()->toArray();
        $data = Advertise::where('user_id', $userId)->whereIn('id', $assignedIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Assigned advertise list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $userId = Auth::id();

        $assignedIds = SectionAdvertise::where('user_id', $userId)->pluck('advertise_id')->unique()->values()->toArray();
        $data = Advertise::where('user_id', $userId)->whereNotIn('id', $assignedIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned advertise list.',
            'data' => $data
        ], 200);
    }
}
