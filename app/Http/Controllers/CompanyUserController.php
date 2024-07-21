<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Enums\Role;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\UserInvitation;
use Illuminate\Support\Str;
use App\Mail\RegistrationInvite;
use Illuminate\Support\Facades\Mail;

class CompanyUserController extends Controller
{
    public function index(Company $company)
    {
        Gate::authorize('viewAny', $company); 

        $users = $users = $company->users()->where('role_id', Role::COMPANY_OWNER->value)->get();
 
        return view('companies.users.index', compact('company', 'users'));
    }

    public function create(Company $company)
    {
        Gate::authorize('create', $company);

        return view('companies.users.create', compact('company'));
    }
 
    public function store(StoreUserRequest $request, Company $company)
    {
        Gate::authorize('create', $company);

        $email = $request->input('email');

        // Check if the email already exists in the user_invitations table
        $existingInvitation = UserInvitation::where('email', $email)->first();

        if ($existingInvitation) {
            // If the email already exists, set an error in the session
            session()->flash('errors', ['email' => 'Invitation with this email address already requested.']);

            // 2. Return an error response
            // return response()->json(['error' => 'Email already has a pending invitation'], 400);
            // return to_route('companies.users.index', $company);
            // Use the session()->get('errors') method to ensure a MessageBag instance
            return redirect()->back()->withErrors(session()->get('errors'));
        } else {
            // Create a new invitation
            $invitation = UserInvitation::create([
                'email' => $email,
                'token' => Str::uuid(),
                'company_id' => $company->id,
                'role_id' => Role::COMPANY_OWNER->value,
            ]);

            Mail::to($email)->send(new RegistrationInvite($invitation));

            return to_route('companies.users.index', $company);
        }
    }
 
    public function edit(Company $company, User $user)
    {
        Gate::authorize('update', $company); 

        return view('companies.users.edit', compact('company', 'user'));
    }
 
    public function update(UpdateUserRequest $request, Company $company, User $user)
    {
        Gate::authorize('update', $company); 

        $user->update($request->validated());
 
        return to_route('companies.users.index', $company);
    }

    public function destroy(Company $company, User $user)
    {
        Gate::authorize('delete', $company); 

        $user->delete();
 
        return to_route('companies.users.index', $company);
    }
}
