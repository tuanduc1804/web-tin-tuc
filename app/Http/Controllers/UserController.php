<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;
class UserController extends Controller
{
	public function getDanhSach(){
		$user = User::all();
		return view('admin.user.danhsach',['user'=>$user]);
	}
	public function getThem(){
		return view('admin.user.them');
	}
	public function postThem(Request $request){
		$this->validate($request,
			[
				'name'=>'required|min:3',
				'email'=>'required|unique:users,name',
				'password'=>'required|min:3',
				'passwordAgain'=>'required|same:password'
			],
			[
				'name.required'=>'Bạn chưa nhập Tên người dùng',
				'name.min' => 'Tên người dùng phải lớn hơn 3 ký tự',
				'email.required'=>'Bạn chưa nhập Email',
				'email.email'=>'Bạn chưa nhập đúng định dạng Email',
				'email.unique'=>'Email đã tồn tại',
				'password.required'=>'Bạn chưa nhập mật khẩu',
				'password.min'=>'Mật khẩu phải lớn hơn 3 ký tự',
				'password.same'=>'Hai mật khẩu phải giống nhau'
			]);
		$user = new User;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->quyen = $request->quyen;
		$user->save();
		return redirect('admin/user/them')->with('thongbao','Thêm thành công');
		
	}
	public function getSua($id){
		$user = User::find($id);
		return view('admin.user.sua',['user'=>$user]);
	}
	public function postSua(Request $request,$id)
	{
		$this->validate($request,
			[
				'name'=>'required|min:3'
			],
			[
				'name.required'=>'Bạn chưa nhập Tên người dùng',
				'name.min' => 'Tên người dùng phải lớn hơn 3 ký tự'
			]);
		$user = User::find($id);
		$user->name = $request->name;
		$user->quyen = $request->quyen;

		if($request->changePassword == "on")
		{
			$this->validate($request,
			[
				'password'=>'required|min:3',
				'passwordAgain'=>'required|same:password'
			],
			[
				'password.required'=>'Bạn chưa nhập mật khẩu',
				'password.min'=>'Mật khẩu phải lớn hơn 3 ký tự',
				'password.same'=>'Hai mật khẩu phải giống nhau'
			]);
			$user->password = bcrypt($request->password);
		}

		$user->save();
		return redirect('admin/user/sua/'.$id)->with('thongbao','Sửa thành công');
	}
	public function getXoa($id){
		$user = User::find($id);
		$user->delete();
		return redirect('admin/user/danhsach')->with('thongbao','Xóa User thành công');
	}

	public function getDangnhapAdmin(){
		return view('admin.login');
	}
	public function postDangnhapAdmin(Request $request)
	{
		$this->validate($request,
			[
				'email'=>'required',
				'password'=>'required'
			],
			[
				'email.required'=>'Bạn chưa nhập Email',
				'password.required'=>'Bạn chưa nhập mật khẩu'
			]);
		if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
		{
			return redirect('admin/theloai/danhsach');
		}
		else
		{
			return redirect('admin/dangnhap')->with('thongbao','Đăng nhập thất bại');
		}
	}
	public function getDangXuatAdmin()
	{
		Auth::logout();
		return redirect('admin/dangnhap');
	}
}
