<?php
    #trả về standard consistent jason reponse to client
    class Response{
        private $_success;
        private $_httpStatusCode;
        private $_messages = array();
        private $_data;
        #cache respones -> nếu khách hàng refresh browser hoặc request thì không cần phải quay lại server lấy data
        private $_toCache = false;
        private $_responseData = array();

        public function setSuccess($success){
            $this->_success = $success;
        }
        public function setHttpStatusCode($httpStatusCode){
            $this->_httpStatusCode = $httpStatusCode;

        }
        public function addMessage($message) {
            #dấu ngoặc vuông là hiệu lệnh append - thêm vào  
            $this->_messages[] = $message;
        }
        public function setData($data) {
            $this->_data = $data;
        }
        public function toCache($toCache) {
            $this->_toCache = $toCache;
        }
        #Gửi response object tới browser dưới dạng json
        public function send() {
            #set header của response dưới dạng utf-8
            header('Content-type:application/json;charset=utf-8');
            

            #nếu có có thể cache
            if($this->_toCache == true) {
                #lưu giữ 60s
                header('Cache-Control: max-age=0');
            }
            else {
                header('Cache-Control: no-cache, no-store');
            }
    
            # các trường hợp response bị lỗi -> trả về lỗi
            if(!is_numeric($this->_httpStatusCode) || ($this->_success !== false && $this->_success !== true )) {
                # lỗi 500
                http_response_code(500);
                # set value cho trường status code, tương tự với các trường bên dưới
                $this->_responseData['statusCode'] = 500;
                $this->_responseData['success'] = false;
                # set thông báo xuất hiện lỗi
                $this->addMessage("Response creation error");
                $this->_responseData['messages'] = $this->_messages;
            }
            # Các trường hợp thành công
            else {
                
                http_response_code($this->_httpStatusCode);
                $this->_responseData['statusCode'] = $this->_httpStatusCode;
                $this->_responseData['success'] = $this->_success;
                $this->_responseData['messages'] = $this->_messages;
                $this->_responseData['data'] = $this->_data;
            }
            #trả dữ liệu về cho browser thành json
            echo json_encode($this->_responseData);
        }
    
    }
?>