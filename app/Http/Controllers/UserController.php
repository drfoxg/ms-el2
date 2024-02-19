<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::where('id', '<>', auth()->user()->id)->orderBy('id', 'asc')->paginate(10);
        //$users = User::orderBy('id', 'asc')->paginate(10);

        return view('dashboard.index', [
            'users' => $users,
            'tePaginatorActive' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {

        $this->authorize('create', User::class);
        //dd('store');

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'lowercase', 'email:rfc', 'max:255', Rule::unique(User::class)],
            'password' => 'required|string|min:8|max:50',
            'is_admin' => 'required_if:yes,on,1',
        ]);

        /*
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            //'password' => Hash::make($validated['password']),
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'],
        ]);
        */

        User::create($request->except('_token'));

        return redirect()->route('dashboard.index')->withSuccess('Пользователь был создан успешно.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('dashboard.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        //dump($user);

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'lowercase', 'email:rfc', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'is_admin' => 'required_if:yes,on,1',
        ]);

        //dump($validated['is_admin']);

        if (isset($validated['is_admin'])) {
            $isAdmin = 'on';
        } else {
            $isAdmin = 0;
        }


        //dump($isAdmin);

        //$user->update($request->all());

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $isAdmin,
        ]);


        //dump($user);

        return redirect()->route('dashboard.index')->withSuccess('Данные Пользователя были обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('dashboard.index')->withSuccess('Пользователь был удален успешно.');
    }
}
