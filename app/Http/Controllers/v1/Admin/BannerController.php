<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerStoreRequest;
use App\Http\Requests\BannerUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Banner;
use App\Models\SectionBanner;
use App\Traits\BannerTrait;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    use BannerTrait;

    public function banners(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $banner = Banner::where('user_id', $userId);

        if ($search) {
            $banner = $banner->where('title', 'LIKE', '%' . $search . '%');
        }

        if ($sort) {
            switch ($sort) {
                case 'asc':
                    $banner->orderBy('title', $sort);
                    break;
                case 'desc':
                    $banner->orderBy('title', $sort);
                    break;
            }
        } else {
            $banner = $banner->latest();
        }

        if ($isActive) {
            $banner = $banner->get();
        } else {
            $banner = $banner->paginate();
        }

        return Response::json([
            'status' => '200',
            'message' => 'Banners list get successfully',
            'data' => $banner
        ], 200);
    }

    public function store(BannerStoreRequest $request)
    {
        $userId = Auth::id();

        $image = $request->file('image');

        $name = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/images/banners');

        $image->move($destinationPath, $name);

        if ($request->schedule_start_date) {
            $status = 'inactive';
        } else {
            $status = $request->status;
        }

        $banner = new Banner;
        $banner->user_id = $userId;
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->target = $request->target;
        $banner->target_value = $request->target_value;
        $banner->image = $name;
        $banner->showtype = 'mobile';
        $banner->status = $status;
        $banner->schedule_start_date = isset($request->schedule_start_date) && !empty($request->schedule_start_date) ? (new \DateTime($request->schedule_start_date))->format('Y-m-d') : null;
        $banner->schedule_end_date = isset($request->schedule_end_date) && !empty($request->schedule_end_date) ? (new \DateTime($request->schedule_end_date))->format('Y-m-d') : null;
        $banner->save();

        if ($request->section_id) {
            $this->bannerAssignTosection($banner, $request->section_id);
        }

        return Response::json([
            'status' => '200',
            'message' => 'banner data has been saved'
        ], 200);
    }
    public function get_single_banner(Banner $banner)
    {
        $banner = $banner->load('section_banners.section');

        return Response::json([
            'status' => '200',
            'message' => 'Banner data get successfully',
            'data' => $banner
        ], 200);
    }
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        $userId = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/images/banners');

            $image->move($destinationPath, $name);
        }

        if ($request->schedule_start_date) {
            $status = 'inactive';
        } else {
            $status = $request->status;
        }

        $banner->user_id = $userId;
        $banner->title = $request->title;
        $banner->description = $request->description;
        if ($request->hasFile('image')) {
            $banner->image = $name;
        }
        $banner->target = $request->target;
        $banner->target_value = $request->target_value;
        $banner->showtype = 'mobile';
        $banner->status = $status;
        $banner->schedule_start_date = isset($request->schedule_start_date) && !empty($request->schedule_start_date) ? (new \DateTime($request->schedule_start_date))->format('Y-m-d') : null;
        $banner->schedule_end_date = isset($request->schedule_end_date) && !empty($request->schedule_end_date) ? (new \DateTime($request->schedule_end_date))->format('Y-m-d') : null;
        $banner->save();

        if (isset($request->section_id) && $request->section_id) {
            $this->bannerAssignTosection($banner, $request->section_id);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Banner data updated successfully'
        ], 200);
    }
    public function delete(Banner $banner)
    {
        $banner->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Banner move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_banners()
    {
        $userId = Auth::id();
        $banner = Banner::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash banners list get successfully',
            'data' => $banner
        ], 200);
    }
    public function trash_restore($banner)
    {
        $banner = Banner::onlyTrashed()->findOrFail($banner);
        $banner->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Banner restored successfully'
        ], 200);
    }
    public function trash_delete($banner)
    {
        $banner = Banner::onlyTrashed()->findOrFail($banner);
        $banner->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash banner deleted successfully'
        ], 200);
    }
    public function all_trash_delete()
    {
        $userId = Auth::id();

        Banner::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Banner deleted successfully'
        ], 200);
    }

    public function remove_banner_section(SectionBanner $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Banner has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $userId = Auth::id();

        $bannerIds = SectionBanner::where('user_id', $userId)->pluck('banner_id')->unique()->values()->toArray();
        $data = Banner::where('user_id', $userId)->whereIn('id', $bannerIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Assigned banner list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $userId = Auth::id();

        $bannerIds = SectionBanner::where('user_id', $userId)->pluck('banner_id')->unique()->values()->toArray();
        $data = Banner::where('user_id', $userId)->whereNotIn('id', $bannerIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned banner list.',
            'data' => $data
        ], 200);
    }

    public function statusUpdate(Banner $banner)
    {
        if ($banner->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $banner->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Banner status updated successfully.',
        ], 200);
    }
}
