<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;
use Illuminate\Support\Facades\Auth;
class PageController extends Controller
{
	function __construct()
	{
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);

		if(Auth::check())
		{
			view()->share('nguoidung',Auth::user());
		}
	}
    function trangchu()
    {
    	return view('pages.trangchu');
    }
    function gioithieu()
    {
        return view('pages.gioithieu');
    }
    function lienhe()
    {
    	return view('pages.lienhe');
    }
    function loaitin($id)
    {	
    	$loaitin = LoaiTin::find($id);
    	$tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
    	return view('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id)
    {
    	$tintuc = TinTuc::find($id);
    	$tinnoibat = Tintuc::where('NoiBat',1)->take(4)->get();
    	$tinlienquan = Tintuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
    	return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }

    public function getDangnhap()
    {
    	return view('pages.dangnhap');
    }
    public function postDangnhap(Request $request)
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
    		return redirect('trangchu');
    	}	
    	else
    	{
    		return redirect('dangnhap')->with('thongbao','Đăng nhập thất bại');
    	}
    }
    public function getDangxuat()
    {
    	Auth::logout();
    	return redirect('trangchu');
    }

    public function getNguoidung()
    {   
        return view('pages.nguoidung');
    }
    public function postNguoidung(Request $request)
    {
        $this->validate($request,
            [
                'name'=>'required|min:3'
            ],
            [
                'name.required'=>'Bạn chưa nhập Tên người dùng',
                'name.min' => 'Tên người dùng phải lớn hơn 3 ký tự'
            ]);
        $user = Auth::user();
        $user->name = $request->name;

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
        return redirect('nguoidung')->with('thongbao','Sửa thành công');
        
    }

    public function getDangky()
    {
        return view('pages.dangky');
    }
    public function postDangky(Request $request)
    {
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
                'passwordAgain.same'=>'Hai mật khẩu phải giống nhau'
            ]);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->quyen = 0;
        $user->save();
        return redirect('dangky')->with('thongbao','Đăng ký thành công');
    }

    public function timkiem(Request $request)
    {
        $tukhoa = $request->tukhoa;
        $tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orWhere('TomTat','like',"%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->take(30)->paginate(5);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}
