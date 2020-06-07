<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;

class Article extends Controller
{
    //展示分页
    public function index()
    {
        //检查是否登录
        $this->check_login();
        //显示文章
        $article=Db::table('article')
            ->field('content,title,username,addate,article.id')
            ->alias('a')
            ->join('user u','a.user_id=u.id')
//            ->join('comments c','c.article_id=a.comment_id')
            ->order('id','desc')
            ->paginate(5)
        ;



//        print_r($comment);exit;
//        print_r($article);exit;

         //把分页数据赋值给模板变量
//        $this->assign('article', $article);
//        dump($article);exit;
        return view('index',['article'=>$article]);
    }

    private function check_login()
    {
        session_start();
        if (!$_SESSION['username'])
        {
            $this->error('您还未登录，请登录','/home/login/login');
        }
    }
    //添加文章
    public function add(Request $request)
    {
        if ($request->method()=='GET'){
            return view();
        }
        elseif ($request->method()=='POST'){
            $data=$request->param();
//            echo $data['category_id'];exit;
//            print_r($data);exit;
            //Array ( [title] => 警惕！钟南山担心的这个事情，还是发生了… [content] => 123 [category_id] => 4 [username] => damon )
            session_start();

            $data['user_id']=$_SESSION['id'];
            \app\home\model\Article::create($data);
            \app\home\model\Category::create($data);
            $this->redirect('/home/article/index');
        }

    }


    //删除文章
    public function del(Request $request,$id)
    {
//        echo $id;exit;
        \app\home\model\Article::destroy($id);
        $this->success('删除成功','/home/article/index',null,1);
    }
    //修改
    public function update(Request $request,$id)
    {
        if($request->method()=='GET')
        {
//            echo $id;exit;
//           $art= \app\home\model\Article::get($id);
            $art=Db::table('article')
                ->field('content,title,name,article.id')
                ->alias('a')
                ->join('category u','a.category_id=u.id')
                ->find($id);
//           var_dump($art);exit;
            return view('update',['art'=>$art]);
        }
        else if ($request->method()=='POST')
        {
            $data=$request->param();
//            var_dump($data);exit;
//            echo $id;exit;
            \app\home\model\Article::where('id',$id)->update($data);
            $this->success('修改成功','/home/article/index',null,1);
        }
    }

    //添加文章评论
    public function addcomment(Request $request,$id)
    {
        if ($request->method()=='GET'){
            return view('addcomment',['id'=>$id]);
        }
        elseif ($request->method()=='POST') {
            $data['comment'] = $request->param('comment');

            $data['article_id']=$id;
//            var_dump($data);exit;
            Db::table('comments')
                ->insert($data);

            $c_id=Db::table('comments')
            ->where('article_id',$id)
            ->column('id')
            ;
//echo $id;exit;
            Db::table('article')
                ->where('id',$id)
                ->update(['comment_id'=>$c_id]);
            $this->success('修改成功','/home/article/detail/?id='.$id,null,1);
        }

    }

    public function detail(Request $request,$id)
    {
//        echo $id;
        $detail=Db::table('article')
            ->field('content,title,username,addate,a.id')
            ->alias('a')
            ->where('a.id',$id)
            ->join('user u','u.id=a.user_id')
//            ->join('comments c','c.article_id=a.comment_id')
           ->find();
            ;
            $com=Db::table('comments')
                ->where('article_id',$id)
                ->select();
//        dump($com);exit;
//        dump($detail);exit;
//array(1) { [1111]=> array(5) { ["content"]=> string(4) "1111" ["title"]=> string(5) "11111"
// ["username"]=> string(5) "damon" ["addate"]=> string(19) "2020-06-05 12:47:30" ["id"]=> int(43) } }
            return view('detail',['detail'=>$detail,'com'=>$com]);


    }

}