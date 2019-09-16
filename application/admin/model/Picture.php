<?php

namespace app\admin\model;

use think\Model;

class Picture extends Model
{
    public function upload($file)
    {
        //获取源文件的名称
        $origin_file_name = $file->getInfo("name");
        //上传文件
        $info = $file->validate(['size'=>3145728,'ext'=>'jpg,png,gif'])->move( './uploads');
        if ($info) {
            /**
             * 上传成功获取文件信息
             */
            //获取文件的名称
            $filename = $info->getFilename();
            //获取文件保存的路径
            $savename = $info->getSaveName();
            //转换路径
            $saveName = str_replace("\\","/",$savename);

            //将文件信息写入图片表中
            $picture = self::create([
                "pic_name"     => $filename,  // 文件名称
                "pic_addr"     => $saveName,  //文件的路径
                "upload_time"  => time(),  // 文件上传的时间
            ]);
            //获取图片的id
            $pic_id = $picture->id;
            //返回数据
            return [
                "file_id"            => $pic_id,
                "file_name"          => "uploads/".$saveName,
                "origin_file_name"   => $origin_file_name,
                "state"              => 1,

            ];

        } else {
            //上传失败
            return [
                "state" => 0,
                "msg"   => $file->getError(),
            ];
        }

    }
}