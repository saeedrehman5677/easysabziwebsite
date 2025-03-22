<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Models\DeliveryChargeByArea;
use App\Models\DeliveryChargeSetup;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

class BranchController extends Controller
{
    public function __construct(
        private Branch $branch,
        private DeliveryChargeSetup $deliveryChargeSetup,
        private DeliveryChargeByArea $deliveryChargeByArea
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $queryParam = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $branches = $this->branch->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
            })->orderBy('id', 'desc');
            $queryParam = ['search' => $request['search']];
        }else{
           $branches = $this->branch->orderBy('id', 'desc');
        }
        $branches = $branches->paginate(Helpers::getPagination())->appends($queryParam);
        return view('admin-views.branch.add-new', compact('branches','search'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): Factory|View|Application
    {
        $queryParam = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $branches = $this->branch->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $q->orWhere('id', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $queryParam = ['search' => $request['search']];
        }else{
            $branches = $this->branch->orderBy('id', 'desc');
        }
        $branches = $branches->paginate(Helpers::getPagination())->appends($queryParam);
        return view('admin-views.branch.list', compact('branches','search'));
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request): Redirector|RedirectResponse|Application
    {
        $request->validate([
            'name' => 'required|max:255|unique:branches',
            'email' => 'required|max:255|unique:branches',
            'password' => 'required|min:8|max:255',
            'image' => 'required|max:2048',
        ], [
            'name.required' => translate('Name is required!'),
            'name.unique' => translate('Name must be unique'),
            'email.required' => translate('Email is required!'),
            'email.unique' => translate('Email must be unique'),
            'password.required' => translate('Password is required!'),
            'Image.required' => translate('Image is required!'),
        ]);

        if (!empty($request->file('image'))) {
            $imageName = Helpers::upload('branch/', 'png', $request->file('image'));
        } else {
            $imageName = 'def.png';
        }

        $defaultBranch = $this->branch->find(1);
        $defaultLat = $defaultBranch->latitude ?? '23.777176';
        $defaultLong = $defaultBranch->longitude ?? '90.399452';
        $defaultCoverage = $defaultBranch->coverage ?? 100;

        $branch = $this->branch;
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->latitude = $request->latitude ?? $defaultLat;
        $branch->longitude = $request->longitude ?? $defaultLong;
        $branch->coverage = $request->coverage ?? $defaultCoverage;
        $branch->address = $request->address;
        $branch->password = bcrypt($request->password);
        $branch->image = $imageName;
        $branch->save();

        $branchDeliveryCharge = $this->deliveryChargeSetup;
        $branchDeliveryCharge->branch_id = $branch->id;
        $branchDeliveryCharge->delivery_charge_type = 'fixed';
        $branchDeliveryCharge->fixed_delivery_charge = 0;
        $branchDeliveryCharge->save();

        try {
            $emailServices = Helpers::get_business_settings('mail_config');
            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($branch->email)->send(new \App\Mail\Branch\BranchRegistration($branch, $request->password));
            }
        } catch (\Exception $e) {
        }

        Toastr::success(translate('Branch added successfully!'));
        return redirect('admin/branch/list');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $branch = $this->branch->find($id);
        return view('admin-views.branch.edit', compact('branch'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'unique:branches,email,'.$id.',id'],
            'image' => 'max:2048',
            'password' => 'nullable|min:8|max:255',
        ], [
            'name.required' => translate('Name is required!'),
            'email.required' => translate('Email is required!'),
            'email.unique' => translate('Email must be unique!'),
        ]);

        $branch = $this->branch->find($id);
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->longitude = $request->longitude ? $request->longitude : $branch->longitude;
        $branch->latitude = $request->latitude ? $request->latitude : $branch->latitude;
        $branch->coverage = $request->coverage ? $request->coverage : $branch->coverage;
        $branch->address = $request->address;
        $branch->image = $request->has('image') ? Helpers::update('branch/', $branch->image, 'png', $request->file('image')) : $branch->image;
        if ($request['password'] != null) {
            $branch->password = bcrypt($request->password);
        }
        $branch->save();

        Toastr::success(translate('Branch updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $branch = $this->branch->where('id', $request->id)->whereNotIn('id', [1])->first();

        if ($branch && $branch->delete()) {
            $this->deliveryChargeSetup->where(['branch_id' => $request->id])->delete();
            $this->deliveryChargeByArea->where(['branch_id' => $request->id])->delete();

            try {
                $emailServices = Helpers::get_business_settings('mail_config');
                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($branch->email)->send(new \App\Mail\Branch\BranchDelete($branch));
                }
            } catch (\Exception $e) {
            }

            Toastr::success(translate('Branch removed along with related data!'));
        } else {
            Toastr::error(translate('Failed to remove branch!'));
        }
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $branch = $this->branch->find($request->id);
        $branch->status = $request->status;
        $branch->save();

        try {
            $emailServices = Helpers::get_business_settings('mail_config');
            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($branch->email)->send(new \App\Mail\Branch\BranchChangeStatus($branch));
            }
        } catch (\Exception $e) {
        }
        Toastr::success(translate('Branch status updated!'));
        return back();
    }
}
