<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\User; //model user
use Auth; //auth
use Carbon\Carbon; //carbon

class AuthController extends Controller
{
    //afegit per auth api passport

    //register
    public function register(Request $request) {
        
        //noms inputs register.vue
        $request->validate( [
            'name' => 'required|string|max:255',
            'email'=> 'required|string|email|unique:users',
            'password' => 'required|string|min:4|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if ( $user->save() ) {
            return response()->json([
                'message' => 'Usuari creat ok! ;)',
                'status_code' => 201] //201 created
                , 201);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * registre usuari :(',
                'status_code' => 500]
                , 500);
        }

    }

    //login
    public function login(Request $request) {
        
        $request->validate( [
            'email'=> 'required|string|email',
            'password' => 'required|string|min:4',
            'remember_me' => 'boolean'
        ]);

        //si error autentificació
        if ( !Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => '* FATAL ERROR * usuari/pass',
                'status_code' => 401] //unauthorized 401
                , 401);
        }

        $user = $request->user();

        //crea token amb scope depenent usuari
        if ($user->role == 'admin') {
            $tokenData = $user->createToken('Personal Access Token', ['admin']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['user']);
        }

        $token = $tokenData->token;

        //si 'remember_me' expira en 1 setmana
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        //si token guardat, retorna dades user + token + dades relacionades amb token
        //bearer = portador ?
        if ($token->save()) {
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'access_type' => 'Bearer',
                'token_scope' => $tokenData->token->scopes[0],
                'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString(),
                'status_code' => 200
            ],
            200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * token :(',
                'status_code' => 500]
            , 500);
        }
    }

    //logout
    public function logout(Request $request) {

        //revoquem token accés usuari
        $request->user()->token()->revoke();

        //retornem json logout ok
        return response()->json([
            'message' => 'Logout ok! ;)',
            'status_code' => 200]
            , 200);
    }

    //entregar perfil usuari
    public function profile(Request $request) {

        //si user a la request
        if ($request->user()) {
            return response()->json($request->user(), 200);
        }

        //si no
        return response()->json([
            'message' => 'Not logged in! :(',
            'status_code' => 500]
            , 500);
    }

    //modificar perfil
    public function update_profile(Request $request)
    {   
        /*
        echo($request);
        echo($request->name);
        echo($request->email);
        echo($request->user());
        echo($request->user()->id);
        */

        //agafem user
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 
                            Rule::unique('users')->ignore($user->id)],
        ]);

        //canviem dades
        $user->name = $request->name;
        $user->email = $request->email;

        //si user save
        if ( $user->save() ) {
            return response()->json($user, 200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * update :(',
                'status_code' => 500]
                , 500);
        }
 
    }

    //modificar password
    public function update_password(Request $request)
    {   
        //echo($request);
        //echo($request->user());
        //echo($request->user()->id);

        //agafem user
        $user = $request->user();

        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:4|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, $user->password)) {
            //return back()->with("error", "* FATAL ERROR* Password vell incorrecte");
            return response()->json([
                'message' => '*FATAL ERROR* Password vell incorrecte',
                'status_code' => 500]
                , 500);
        }

        #Match Old i New
        if( $request->old_password == $request->new_password ) {
            //return back()->with("error", "* FATAL ERROR * El nou pass ha de ser diferent que el vell");
            return response()->json([
                'message' => '*FATAL ERROR* Nou pass ha de ser diferent al vell',
                'status_code' => 500]
                , 500);
        }
    
        //Update the new Password
        /*
        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        return back()->with("status", "Password canviat OK ;)");
        */

        //$user->password = bcrypt($request->password);
        $user->password = Hash::make($request->new_password);

        //si pass save
        if ( $user->save() ) {
            return response()->json($user, 200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * update pass :(',
                'status_code' => 500]
                , 500);
        }

    }
}
