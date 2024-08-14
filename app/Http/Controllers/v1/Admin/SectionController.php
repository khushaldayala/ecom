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
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    use SectionTrait;

    public function sections(){
        $userId = Auth::id();
        $sections = Section::where('user_id', $userId)->get();

        foreach ($sections as $section) {
            $sectionCount = $this->assignedItemsCount($section);
            $section->items_count = $sectionCount;
            $assignedItems = $this->assignedItems($section)->toArray();
        }

        return Response::json([
            'status' => '200',
            'message' => 'Sections list get successfully',
            'data' => $sections
        ], 200);
    }
    public function store(SectionStoreRequest $request){

        $userId = Auth::id();
        $maxOrder = Section::max('order');
        if(!$maxOrder)
        {
            $maxOrder = 0;
        }
        $maxOrder++;

        $section = new Section;
        $section->user_id = $userId;
        $section->title = $request->title;
        $section->description = $request->description;
        $section->keywords = $request->keywords;
        $section->keyword_option = $request->keyword_option;
        $section->end_point = $request->end_point;
        $section->order = $maxOrder;
        $section->dlink = $request->dlink;
        $section->status = $request->status;
        $section->save();

        if($request->assignIds)
        {
            $this->assignToSection($request->keywords, $section, $request->assignIds);
        }

        return Response::json([
            'section_id' => $section->id,
            'status' => '200',
            'message' => 'section data has been saved'
        ], 200);
    }
    public function get_single_section(Section $section){
        $assigned_data = $this->assignedItems($section);

        return Response::json([
            'status' => '200',
            'message' => 'Sections data get successfully',
            'data' => $assigned_data,
        ], 200);
    }
    public function update_section(SectionUpdateRequest $request, Section $section){
        $userId = Auth::id();

        $section->user_id = $userId;
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

        return Response::json([
            'status' => '200',
            'message' => 'section data has been Updated'
        ], 200);
    }
    public function delete(Section $section){
        $this->removeItemsSection($section);
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Section move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_section(){
        $userId = Auth::id();
        $section = Section::where('user_id', $userId)->onlyTrashed()->get();

        return Response::json([
            'status' => '200',
            'message' => 'Trash sections list get successfully',
            'data' => $section
        ], 200);
    }
    public function trash_restore_section($section){
        $section = Section::onlyTrashed()->findOrFail($section);
        $section->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Section restored successfully'
        ], 200);
    }
    public function trash_delete_section($section){
        $section = Section::onlyTrashed()->findOrFail($section);
        $section->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash section deleted successfully'
        ], 200);
    }
    public function all_trash_delete_section(){
        $userId = Auth::id();
        Section::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Section deleted successfully'
        ], 200);
    }

    public function statusUpdate(Section $section)
    {
        if ($section->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $section->update([
            'status' => $status
        ]);

        return Response::json([
            'status' => '200',
            'message' => 'Section status updated successfully.',
        ], 200);
    }

    public function preview(Section $section)
    {
        $section = $this->assignedItemsPreview($section);

        return Response::json([
            'status' => '200',
            'message' => 'Section preview get successfully',
            'data' => $section
        ], 200);
    }

    public function previewAll()
    {
        $userId = Auth::id();
        $sections = Section::where('user_id', $userId)->get();

        foreach ($sections as $section) {
            $this->assignedItemsPreview($section);
        }
        
        return Response::json([
            'status' => '200',
            'message' => 'Section preview get successfully',
            'data' => $sections
        ], 200);
    }
    
    public function reorder(Request $request)
    {
        $sections = $request->input('sections');
        if (!$sections) {
            return response()->json(['message' => 'No sections provided'], 400);
        }

        foreach ($sections as $index => $id) {
            Section::where('user_id', Auth::id())->where('id', $id)->update(['order' => $index+1]);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Sections reordered successfully'
        ], 200);
    }
}
