<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionStoreRequest;
use App\Http\Requests\SectionUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Section;
use App\Traits\SectionTrait;

class SectionController extends Controller
{
    use SectionTrait;

    public function sections()
    {
        $sections = Section::all();

        foreach ($sections as $section) {
            $sectionCount = $this->assignedItemsCount($section);
            $section->items_count = $sectionCount;
        }

        if ($sections) {
            return Response::json([
                'status' => '200',
                'message' => 'Sections list get successfully',
                'data' => $sections
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Sections data not found'
            ], 404);
        }
    }
    public function store(SectionStoreRequest $request)
    {

        $maxOrder = Section::max('order');
        if (!$maxOrder) {
            $maxOrder = 0;
        }
        $maxOrder++;

        $section = new Section;
        $section->title = $request->title;
        $section->description = $request->description;
        $section->keywords = $request->keywords;
        $section->keyword_option = $request->keyword_option;
        $section->end_point = $request->end_point;
        $section->order = $maxOrder;
        $section->dlink = $request->dlink;
        $section->status = $request->status;
        $section->save();

        if ($request->assignIds) {
            $this->assignToSection($request->keywords, $section, $request->assignIds);
        }

        if ($section) {
            return Response::json([
                'section_id' => $section->id,
                'status' => '200',
                'message' => 'section data has been saved'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'section data has been not saved'
            ], 401);
        }
    }
    public function get_single_section($id)
    {
        $section = Section::findorfail($id);
        $assigned_data = $this->assignedItems($section);
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'Sections data get successfully',
                'data' => $assigned_data,
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Sections data not found'
            ], 404);
        }
    }
    public function update_section(SectionUpdateRequest $request, $id)
    {

        $section = Section::find($id);
        $section->title = $request->title;
        $section->description = $request->description;
        $section->keywords = $request->keywords;
        $section->keyword_option = $request->keyword_option;
        $section->end_point = $request->end_point;
        $section->order = $request->order;
        $section->dlink = $request->dlink;
        $section->status = $request->status;
        $section->save();

        if ($request->assignIds) {
            $this->assignToSection($request->keywords, $section, $request->assignIds);
        }

        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'section data has been Updated'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'section data has been not Updated'
            ], 401);
        }
    }
    public function delete($id)
    {
        $section = Section::find($id);
        if ($section) {
            $this->removeItemsSection($section);
            $section->delete();
        }
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'Section move to trash successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Section has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_section()
    {
        $section = Section::onlyTrashed()->get();
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'Trash sections list get successfully',
                'data' => $section
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Trash sections data not found'
            ], 404);
        }
    }
    public function trash_restore_section($id)
    {
        $section = Section::onlyTrashed()->findOrFail($id);
        $section->restore();
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'Section restored successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Section has been not restored'
            ], 401);
        }
    }
    public function trash_delete_section($id)
    {
        $section = Section::onlyTrashed()->findOrFail($id);
        $section->forceDelete();
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'Trash section deleted successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Section has been not deleted'
            ], 401);
        }
    }
    public function all_trash_delete_section()
    {
        $section = Section::onlyTrashed()->forceDelete();
        if ($section) {
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Section deleted successfully'
            ], 200);
        } else {
            return Response::json([
                'status' => '401',
                'message' => 'Section has been not deleted'
            ], 401);
        }
    }
}
