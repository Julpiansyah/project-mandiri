<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi
        $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|min:10',
            'phone'    => 'required|min:12',
            'password' => 'required|min:8',
        ], [
            'name.required'     => 'Nama User wajib diisi',
            'name.min'          => 'Nama User minimal 3 karakter',
            'email.required'    => 'Email User wajib diisi',
            'email.min'         => 'Email User minimal 10 karakter',
            'phone.required'    => 'No. Telepon wajib diisi',
            'phone.min'         => 'No. Telepon minimal 12 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 8 karakter',
        ]);
        $createUser = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);
        // redirect / perpindahan halaman
        if ($createUser) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil membuat data pengguna!');
        } else {
            return redirect()->back()->with('failed', 'gagal membuat data pengguna!');
        }
    }

    public function signUp(Request $request)
    {

        // (Request $request) : class untuk mengambil value dari formulir
        // validasi
        $request->validate([
            // 'name_input' => 'tipe validasi'
            // required : wajib diisi, min : minimal karakter (teks)
            'first_name' => 'required|min:3',
            'last_name'  => 'required|min:3',
            // email:dns => emailnya valid, @gmail @company.co dsb
            'email'      => 'required|email:dns',
            'password'   => 'required|min:8',
        ], [
            // pesan error custom
            // 'name_input.validasi' => 'pesan'
            'first_name.required' => 'Nama depan wajib diisi',
            'first_name.min'      => 'Nama depan minimal 3 karakter',
            'last_name.required'  => 'Nama belakang wajib diisi',
            'last_name.min'       => 'Nama belakang minimal 3 karakter',
            'email.required'      => 'Email wajib diisi',
            'email.email'         => 'Email tidak valid',
            'password.required'   => 'Password wajib diisi',
            'password.min'        => 'Password minimal 8 karakter',
            'phone.required'     => 'No. Telepon wajib diisi',
            'phone.min'          => 'No. Telepon minimal 12 karakter',
        ]);
        // create () :membuat data baru
        $createUser = User::create([
            // 'nama _clumn' => @request->name_input
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            // hash: enskripsi data (mengubah menjadi karakter acak) agar tidak ada yang bisa menebak isinya
            'password' => Hash::make($request->password),
            // pengguna tidak bisa memilih role (akses), jadi manual di tambahkan 'user'
            'role'     => 'user',
        ]);
        if ($createUser) {
            // redirect() : memindahkan halaman, route() : name routing yang di tuju
            // with(): mengirimkan session , biasanya untuk notifikasi
            return redirect()->route('login')->with('success', 'silahkan login!');
        } else {
            return redirect()->route('register')->with('error', 'Pendaftaran gagal, silahkan coba lagi.');
        }
    }
    public function loginAuth(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ], [
            'email.required'    => 'Email harus diisi',
            'password.required' => 'password harus diisi',
        ]);
        // mengambil data yang akan diverifikasi
        $data = $request->only(['email', 'password']);
        // Auth:: -> class laravel untuk penanganan authentification
        // attempt() -> method class auth untuk mencocokan email-pw atau username-pw kalau cocok akan disimpan datanya ke session auth
        if (Auth::attempt($data)) {
            // jika berhasil login (attempt), dicek lagi role nya
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'berhasiil Login');
            } else {
                return redirect()->route('home')->with('success', 'Berhasil login!');
            }
        } else {
            return redirect()->back()->with('error', 'Email atau password tidak sesuai!');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Berhasil logout! silahkan login kembali untuk akses lengkap');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|min:10',
            'password' => 'required|min:8',
            'phone'    => 'required|min:12',
        ], [
            'name.required'     => 'Nama Bioskop Harus Diisi',
            'name.min'          => 'Nama Wajib Diisi Minimal 3 huruf',
            'email.required'    => 'Email bioskop wajib diisi',
            'email.min'         => 'Email Wajib Diisi Minimal 10 Huruf',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 8 karakter',
            'phone.required'    => 'No. Telepon wajib diisi',
            'phone.min'         => 'No. Telepon minimal 12 karakter',
        ]);

        // kirim data
        $updateUser = User::where('id', $id)->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        if ($updateUser) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil mengubah data pengguna!');
        } else {
            return redirect()->back()->with('failed', 'Gagal mengubah data pengguna!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteData = User::where('id', $id)->delete();

        if ($deleteData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data pengguna!');
        } else {
            return redirect()->back()->with('failed', 'Gagal menghapus data pengguna!');
        }
    }

    public function trash()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data promo!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

    public function datatables()
    {
        $users = User::select('id', 'name', 'email', 'phone', 'role', 'created_at')->get();
        return response()->json($users);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
