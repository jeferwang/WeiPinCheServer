<?php

namespace app\api\controller;


use think\Controller;

class File extends Controller
{
	public function upload()
	{
		$file = request()->file('file');
		$info = $file->validate(['size' => 1024 * 1024 * 2, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
		if ($info) {
			return [
				'status' => 'success',
				'msg' => '上传成功',
				'data' => [
					'file_path' => '/uploads/' . str_replace('\\', '/', $info->getSaveName())
				]
			];
		} else {
			return [
				'status' => 'error',
				'msg' => $file->getError()
			];
		}
	}
}