<?php


namespace App\Http\Controllers;


class UserController
{
    public function show(User $user) : View
    {
        return view('admin.users.show')->with('user', $user);
    }


    public function edit(): View
    {
        // Get authenticated user
        $user = auth()->user();
        // TOdo what if I remove ??
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::all()
        ]);
    }

    public function update(UpdateUserRequest $request): RedirectResponse
    {
        /**
         * If validation fails, a redirect response will be
         * generated to send the user back to their previous
         * location.
         * The errors will also be flashed to the session so
         * they are available for display. If the request
         *  was an AJAX request, a HTTP response with a 422
         * status code will be returned to the user including
         * a JSON representation of the validation errors.
         */
        $validated_data = $request->validated();
        $user = auth()->user();
        $this->authorize('update', user);
        $user->update($request->validated());

        //Session::flash('success', 'User added succesfully');
        return redirect()->route('users.edit')->withSuccess('User updated successfully');
    }
}