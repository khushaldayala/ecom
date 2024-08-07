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

class AdvertiseController extends Controller
{
    use AdvertiseTrait;

    public function advertises()
    {
        $advertise = Advertise::all();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise list get successfully',
                'data' => $advertise
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Advertise data not found'
            ], 404);
        }
    }
    public function store(AdvertiseStoreRequest $request)
    {

        $image = $request->file('image');

        $name = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/images/advertises');

        $image->move($destinationPath, $name);

        $advertise = new advertise;
        $advertise->title = $request->title;
        $advertise->description = $request->description;
        $advertise->image = $name;
        $advertise->link = $request->link;
        $advertise->status = $request->status;
        $advertise->save();

        if ($request->section_id) {
            $this->advertiseAssignTosection($advertise, $request->section_id);
        }

        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise data has been saved'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Advertise data has been not saved'
            ], 401);
        }
    }
    public function get_single_advertise($id)
    {
        $advertise = Advertise::with('section_advertise.section')->findorfail($id);
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise data get successfully',
                'data' => $advertise
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Advertise data not found'
            ], 404);
        }
    }
    public function update(AdvertiseUpdateRequest $request, $id)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/images/advertises');

            $image->move($destinationPath, $name);
        }

        $advertise = advertise::find($id);
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

        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise data has been updated'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Advertise data has been not updated'
            ], 401);
        }
    }
    public function delete($id)
    {
        $advertise = Advertise::find($id);
        $advertise->delete();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise move to trash successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Advertise has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_advertise()
    {
        $advertise = Advertise::onlyTrashed()->get();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Trash Advertise list get successfully',
                'data' => $advertise
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Trash Advertise data not found'
            ], 404);
        }
    }
    public function trash_advertise_restore($id)
    {
        $advertise = Advertise::onlyTrashed()->findOrFail($id);
        $advertise->restore();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Vertise restored successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Vertise has been not restored'
            ], 401);
        }
    }
    public function trash_advertise_delete($id)
    {
        $advertise = Advertise::onlyTrashed()->findOrFail($id);
        $advertise->forceDelete();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'Trash Advertise deleted successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Advertise has been not deleted'
            ], 401);
        }
    }
    public function all_trash_advertise_delete()
    {
        $advertise = Advertise::onlyTrashed()->forceDelete();
        if ($advertise) {
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Advertises deleted successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Advertises has been not deleted'
            ], 401);
        }
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
        $assignedIds = SectionAdvertise::pluck('advertise_id')->unique()->values()->toArray();
        $data = Advertise::whereIn('id', $assignedIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Assigned advertise list.',
            'data' => $data
        ],200);
    }

    public function unassigned()
    {
        $assignedIds = SectionAdvertise::pluck('advertise_id')->unique()->values()->toArray();
        $data = Advertise::whereNotIn('id', $assignedIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned advertise list.',
            'data' => $data
        ], 200);
    }
}
