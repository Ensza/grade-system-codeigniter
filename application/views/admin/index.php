<html>
    <?php $this->load->view('head'); ?>
    <body>
        <div class="flex w-full h-full text-sm flex-col md:flex-row relative">
            <div class="w-full md:w-[20em] bg-slate-700 flex flex-col">
                <h2 class="text-end text-xl me-5 mt-4 text-slate-50 font-light">Admin</h2>
                <div class="mt-4 text-end text-slate-200 h-full">
                <?php
                    foreach($subjects as $subject){
                        echo '<a href="/admin/subject/'.$subject['id'].'" class="block py-2 px-5 hover:bg-slate-800 hover:text-slate-50">'.$subject['name'].'</a>';
                    }
                ?>
                <a href="/admin/ranking" class="block py-2 px-5 hover:bg-slate-800 hover:text-slate-50">Student ranking</a>
                </div>

                <button id="logout" class="w-full py-2 px-5 bg-red-500 hover:bg-red-600 text-slate-50">Logout</button>
            </div>

            <!-- main -->
            <div class="w-full bg-slate-100 p-2 h-full">
                
            </div>

            <?php $this->load->view('loader'); ?>
        </div>
    </body>
    <footer>
        <script>
            var host = 'http://localhost';

            $('#logout').on('click', function(){
                displayLoader('loader');

                ajax(host+'/api/logout', 'GET', getCookie('api_token'), function(data){
                    location.reload();
                });
            });
        </script>
    </footer>
</html>