<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use ClassPreloader\Config;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Input;

    class HomeController extends Controller
    {
        /**
         * Create a new controller instance.
         */
        public function __construct()
        {
            //$this->middleware('auth');
        }

        /**
         * Show the application dashboard.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $shop = Input::get('shop');
            $server = config('shopify.domain');
            if (!preg_match("|[a-z\-]{3,100}\.${server}|", $shop)) {
                $shop = null;
            }

            return view('app.install', [
                'title' => 'Install app',
                'shop'  => $shop,
            ]);
        }

        public function install(Request $request)
        {
            $shop = $request->input('shop');

            if (!!$shop) {
                $server = config('shopify.domain');
                if (preg_match("|[a-z\-]{3,100}\.${server}|", $shop)) {

                    //save shop and
                    $sh = new \ShopifyClient($shop, '', config('shopify.key'), config('shopify.secret'));
                    $installUrl = $sh->getAuthorizeUrl(config('shopify.scopes'), config('shopify.redirect_uri'));
                    $installUrl .= '&=' . urlencode(config('shopify.scopes'));

                    return redirect($installUrl);
                }
            }

            $request->session()->flash('invalid_shop', 'Invalid shop url');

            return redirect('/');
        }
    }
