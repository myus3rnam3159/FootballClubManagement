#NGUỒN THAM KHẢO

Reload windows: https://www.cloudhadoop.com/angular-reload-component/ 

ngx-bootstrap: https://www.tutorialspoint.com/ngx_bootstrap/ngx_bootstrap_pagination.htm

oservable trong angular:  
https://stackoverflow.com/questions/56116905/update-list-when-new-item-is-added?answertab=trending#tab-top  

ngDestroy:  
https://dev.to/angular/making-upgrades-to-angular-ngondestroy-376a  

reset form trong angular: https://stackoverflow.com/questions/54937287/how-to-clear-an-angular-reactive-form-input-values/67567833#67567833

prefilled form trong angular: https://stackoverflow.com/questions/43448923/pre-populate-input-field-in-formgroup-angular2

regex không chạy trong angular nhưng chạy được trong test (issue): https://stackoverflow.com/questions/56097782/regex-not-working-in-angular-validators-pattern-while-working-in-online-regex/56098660#56098660

chỉnh http header trong client\src\app\core\interceptors\jwt.interceptor.ts

PHẢI DISABLE CORS TRÊN CHROME: https://narayanatutorial.com/forgerock/openam/how-to-disable-cors-in-google-chrome  
C:\Program Files\Google\Chrome\Application\chrome.exe --disable-web-security --disable-gpu --user-data-dir=~/chromeTemp

enable header module trong apache:vô C:\Apache24\conf\httpd.conf -> bỏ dấu thăng ở LoadModule headers_module modules/mod_headers.so

Thứ tự xây dựng một component trong angular: service->component->routing->module->template->style->app-routing

Test regex: https://regex101.com/r/1D9Myt/1

Phải lấy error message gốc của mysql db: không được tự chế message: player controller 259

Sử dụng ngIf vời ngFor trong Angular: https://stackoverflow.com/questions/49404844/how-to-combine-ngif-with-ngfor

Thêm vào đầu array trong js: https://stackoverflow.com/questions/8073673/how-can-i-add-new-array-elements-at-the-beginning-of-an-array-in-javascript

Cắt array trong php:  
https://stackoverflow.com/questions/1252673/how-can-i-select-rows-in-mysql-starting-at-a-given-row-number

Concate string trong php: https://stackoverflow.com/questions/5344047/how-to-concat-string-in-php

Lấy độ dài một mảng trong php: https://www.freecodecamp.org/news/php-array-length-how-to-get-an-array-size/

And operator trong php: https://www.w3docs.com/snippets/php/how-to-use-the-and-operator-in-php.html

Biến global trong php: https://stackoverflow.com/questions/5492931/global-variables-in-php

Parameter binding with mysql LIKE query: https://stackoverflow.com/questions/11068230/using-like-in-bindparam-for-a-mysql-pdo-query

Rewrite rules: https://serverfault.com/questions/210734/url-rewrite-with-multiple-parameters-using-htaccess

Regex cho fullname: https://stackoverflow.com/questions/44117024/regex-pattern-for-fullname

Lỗi Cors khi chạy nhiều localhost trên chrome:  
tải extension: https://chrome.google.com/webstore/detail/moesif-origin-cors-change/digfbfaphojjndkpccljibejjbppifbc/related  
https://stackoverflow.com/questions/28547288/no-access-control-allow-origin-header-is-present-on-the-requested-resource-err  
https://stackoverflow.com/questions/57009371/access-to-xmlhttprequest-at-from-origin-localhost3000-has-been-blocked

Tạo router cho module lỗi thường gặp: https://stackoverflow.com/questions/47652201/router-link-attribute-within-the-button-tag-doesnt-work/47652406#47652406

Tạo favicon cho website: lên reddit hỏi

Lỗi không load được image trong angular: https://stackoverflow.com/questions/50649812/angular-failed-to-load-images-404-not-found

convert tử string sang int trong php: https://www.tutorialkart.com/php/php-convert-string-to-int/

chọn dòng theo khoảng trong mysql:https://stackoverflow.com/questions/14068815/how-can-i-select-rows-by-range  

Khóa học tham khảo:  
https://www.udemy.com/course/create-a-rest-api-using-basic-php-with-token-authentication/  
https://www.udemy.com/course/learn-to-build-an-e-commerce-app-with-net-core-and-angular/

#HƯỚNG DẪN CÀI ĐẶT

configure apache server để đọc file .htaccess của server:  
1. Vào Nơi chứa file conf của apache - vd: C:\Apache24\conf\httpd.conf  
2. Tìm "DocumentRoot" chuyển 'AllowOverride None' -> All.  

enable chế độ rewrite url trên apache:  
1. Vào Nơi chứa file conf của apache - vd: C:\Apache24\conf\httpd.conf  
2. Tìm "mod_rewrite"  và xóa dấu # ở trước  
3. Restart lại apache server  
=> Tương tự cho các chế độ khác.  

Chú phần rewrite url trong .htaccess: url request gửi tử postman sẽ được convert thành url theo cấu trúc folder server nên phái xem.

lấy phần từ đầy tiên của array trong php: https://www.geeksforgeeks.org/how-to-get-the-first-element-of-an-array-in-php/

cách enable extension của php:https://askinglot.com/how-do-i-enable-php-extensions-in-windows  
    1. Vào C:\php\php.ini:  
    2. Tìm 'extension'  
    3. Muốn mở cái nào thì xóa dấu ';' trước ext đó.

đặt tên url cho các yêu cầu của bài tập 2: thể sử dụng dạng ? + = như bên dưới cho php api mà code php vấn giữ nguyên -> test 2 kiểu trong  postman:  
    0.  /features   
    1.  /players  
    2.  /clubs  
    2.1 /players/club/{:clubid} hoặc /players?clubid=x  
    3.  /players?search=TuKhoa hoặc  /players/search/{:TuKhoa}  
    4.  /players/number/{:number} hoặc /players?number=value  
    5.  /players  
    6.  /clubs  
    7.  /clubs/{:clubid} hoặc kiểu dấu hỏi chấm như trên  
    8.  /players/{:playerid} hoặc kiểu dấu hỏi chấm như trên  
    9.  /players/{:playerid} hoặc kiểu dấu hỏi chấm như trên  
    10. /account/login - logout - register hoặc /users cho đăng ký, /sessions cho login và /sessions/{:session} cho logout  
    11. /intro  
    12. Phân trang danh sách:  
    * /players/page/{:page} hoặc /players?pageSize=s&pageIndex=i  
    * tương tự cho club

chạy php bên ngoài htdocs trong ổ C: https://stackoverflow.com/questions/2975027/how-to-run-a-php-file-on-my-computer-that-is-outside-the-htdocs-directory  
    1. Vào htdocs trong ổ C:\Apache2.4  
    2. vào file httpd.conf trong folder conf  
    3. chỉnh DocumentRoot dẫn tới folder chứa file php muốn chạy.  
    *vd: ${SRVROOT}/../Users/war4m/OneDrive/Desktop/SourceRepos/Hcmus/UngDungPhanTan/ONha/18127057_Football/server

phiên bản bootstrap đang dùng: 4.6.0

mysql doc: https://dev.mysql.com/doc/refman/8.0/en/

phím tắt uppercase kí tự trong file:https://stackoverflow.com/questions/35184509/make-selected-block-of-text-uppercase/41688564#41688564

Tạo csdl footballdb trong mysql sử dụng commandline:  
    1. gõ lệnh mysql -u root -p -> nhập password  
    2. 'create database if not exists footballdb;'  
    3. thêm 'use footballdb;' vào đầu script.sql ở dạng utf8 (copy file sql gốc - FootbalDB_full_final_MYSQL.sql  ở dạng utf16 gốc sang)  
    4. Chỉ chừa lại những dòng liên quan đến club, stadium, coach, player trong script.sql  
    5. ở phần player, định dạng cột dob trong file gốc phải giống trong script.sql  
    6. vào mysql workbench, mở file + Kiểm tra lỗi syntax SQL trên và chạy  

Port chuẩn:  
    HTTP: 80  
    HTTPS: 443  
    MySQL: 3306  

Cài:

php:    https://windows.php.net/download#php-8.1  
        1. Phải tải bản thread safe zip  
        2. Giải nén vào c:\php  
        3. Tìm file php8apache2_4.dll -> dùng để connect tới apache với php trong runtime  
        4. Tới folder Apache24\config trong ổ C:  
            a. Mở httpd.conf = vscode  
            b. Kéo xuống dưới cùng và dán:  
                LoadModule php_module "C:/php/php8apache2_4.dll"  <> Nếu là php7 thì phải là php7_module  
                AddHandler application/x-httpd-php .php  
                PHPIniDir "c:/php"  
                save -> đòng file  
        5. Vào c:\php:  
            a. Tìm file php.ini-development -> đây là file init.  
            b. Copy file trên thành một bản mới cùng level -> đổi tên thành php.ini  -> chọn yes  
            c. Start apache service/server -> apache read all conf changes vào memory  
            d. copy file phpinfo.php vào 
            d. copy file phpinfo.php vào C:\Apache24\htdocs để test  
            e. vào localhost/phpinfo.php -> có bảng là OK!  

mysql:  https://dev.mysql.com/downloads/installer/  
        1. Chọn theo default, skip requirement failing  
        2. root password: mysqlrootpw  
        3. username: root
        4. windows service name: MySQL80 -> khởi động cùng máy -> chú ý khi tắt phải vô hiệu hóa service MySQL80 trước  
        * Có thể có nhiều instance của MySQL service installed cùng một lúc trên 1 máy  
        * Phải add C:\Program Files\MySQL\MySQL Server 8.0\bin vào path  
        * reset pass - root:  
            a. Đăng nhập vào mysql với câu lệnh mysql -u root -p + nhập pass cũ.  
            b. chạy ALTER USER 'root'@'localhost' IDENTIFIED BY 'root'; + enter    

ApacheServer on windows:  
        1. https://www.apachelounge.com/download/  
        2. Visual C++ Redistributable for Visual Studio 2015-2022(was 2015-2019 có thể được)  
        3. giải nén file download -> cop folder Apache24 vào ổ C  
        4. Vào folder Apache24/conf mở file httpd.conf = vscode  
        5. Tìm "apache24"  
        6. Dòng sau xác định server root trong system, nếu muốn có thể đổi sang vd "e:/Apache24" hoặc đổi tên folder (trong file vào ở folder)  
        7. Tìm "ServerName"  
        8. Xóa dấu # trong #ServerName www.example.com:80 vào đổi tên thành localhost:80  
        9. Bật terminal-powershell(admin?) trong ổ C(mặc định):/Apache24/bin - nơi chứa toàn bộ binary file của apache  
        10. Gọi lệnh .\httpd.exe  
        11. Allow access  
        12. Doc Web root: C:\Apache24\htdocs - trong này có chứa index.html rendered khi lần đầu chạy server
        13. Tắt server ctrl + c  
    * Chạy apache server như một service  
        a. bật powershell - admin trong apache\bin  
        b. gõ .\httpd.exe -k install  để cài -> install -> uninstall để gỡ cài đặt
        c. vào control panel  
        d. tìm "services" -> view local services  
        e. chọn start để chạy server / net start apache2.4 trong cmdline  
        f. chọn stop để tắt server / net stop apache2.4 trong cmdline  
    * Nhớ tắt service/server riêng khi dùng WAMP  





