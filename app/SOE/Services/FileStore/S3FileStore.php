<?php namespace SOE\Services\FileStore;

class S3FileStore implements FileStoreInterface
{
    public function __construct()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $this->bucketName = 'saveoneverything_uploads';
        $this->s3path = "http://s3.amazonaws.com/";
        $this->s3 = new \S3($awsAccessKey, $awsSecretKey);
    }

    /**
     * Store the given file under the given subpath on S3.
     *
     * @param File $file
     * @param string $subpath
     * @return mixed The path where the file is stored, or false on failure.
     */
    public function store($file, $sub_path = '')
    {
        $temp_file = $file->getRealPath();
        $name = time() . '-' . $file->getClientOriginalName();
        $path = $sub_path.$name;

        // Put our file (also with public read access)
        if ($this->s3->putObjectFile($temp_file, $this->bucketName, $path, \S3::ACL_PUBLIC_READ)) 
        {
            return $this->s3path.$this->bucketName.'/'.$path;
        }

        return false;
    }

    /**
     * Store the given temp file with the given real name under the given subpath on S3.
     *
     * @param tempfile $temp
     * @param string $realname
     * @param string $subpath
     * @return mixed The path where the file is stored, or false on failure.
     */
    public function storeFromTemp($temp, $realname, $sub_path = '')
    {
        $name = time() . '-' . $realname;
        $path = $sub_path.$name;
        $stats = fstat($temp);

        $input = $this->s3->inputResource($temp, $stats['size']);
        // Put our file (also with public read access)
        if ($this->s3->putObject($input, $this->bucketName, $path, \S3::ACL_PUBLIC_READ)) 
        {
            return $this->s3path.$this->bucketName.'/'.$path;
        }

        return false;
    }

    /**
     * Change the S3 bucket.
     *
     * @param string $bucket
     * @return void
     */
    public function setBucket($bucket = 'saveoneverything_uploads')
    {
        $this->bucketName = $bucket;
    }
}