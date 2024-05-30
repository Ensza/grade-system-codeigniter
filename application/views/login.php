<html>
    <?php $this->load->view('head'); ?>

    <body class="bg-slate-200">
        <div class="w-full min-h-[100%] p-2 flex items-center justify-center">
            <div class="bg-slate-50 rounded shadow p-2 text-sm text-slate-600 w-full md:w-[30em] relative">
                <h2 class="text-lg mb-4">Login</h2>
                
                <form id="login">
                    <div class="block mb-2">
                        <label for="" class="block ">Email</label>
                        <input type="text" name="email" class="block px-2 py-1 border rounded w-full">
                    </div>

                    <div class="block mb-2">
                        <label for="" class="block ">Password</label>
                        <input type="password" name="password" class="block px-2 py-1 border rounded w-full">
                    </div>

                    <div class="block mb-2">
                        <button class="px-2 py-1 rounded bg-blue-500 hover:bg-blue-600 text-white shadow">Login</button>
                    </div>
                </form>
                
                <div id="login-failed" style="display: none;" class="rounded p-2 bg-red-50 border border-red-500 text-red-500">
                    Login failed
                </div>

                <?php $this->load->view('loader'); ?>
            </div>
        </div>
    </body>
    <footer>
        <script>
            var host = 'http://localhost';

            // var response = ajax(host+'/api/user', 'GET', getCookie('api_token'));

            // if(response.success){
            //     if(response.data.role == 'admin'){
            //         window.location.href = '/admin';
            //     }else if(response.data.role == 'student'){
            //         window.location.href = '/student';
            //     }
            // }

            $('#login').on('submit', function(e){
                e.preventDefault();
                displayLoader('loader');

                let formdata = $(this).serializeArray();

                $.ajax({
                    url: host+'/api/login',
                    header: 'Content-Type:application/json',
                    type: 'POST',
                    data: formdata,
                    success: function(data){
                        saveCookie('api_token', data.data.token);
                        location.reload();
                    }
                }).fail(function(data){
                    displayLoader('loader', false);
                    $('#login-failed').show();
                });
            });
        </script>
    </footer>
</html>