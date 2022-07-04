package com.riya.canteenautomationapp.userAccount.fragments;


import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.google.android.material.snackbar.Snackbar;
import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.api.ApiClient;
import com.riya.canteenautomationapp.customerSection.HomeActivity;
import com.riya.canteenautomationapp.responses.CustomerLoginResponse;
import com.riya.canteenautomationapp.utils.Constants;
import com.riya.canteenautomationapp.utils.SharedPreferencesUtils;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class CustomerLoginFragment extends Fragment {
    TextView signUpTV;
    Button signInBtn;
    EditText usernameET,passwordET;
    ProgressBar loginProgress;
    CheckBox rememberMe;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_customer_login, container, false);
    }



    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        signUpTV=view.findViewById(R.id.sigupTV);
        signInBtn=view.findViewById(R.id.signInBtn);
        usernameET=view.findViewById(R.id.emailET);
        passwordET=view.findViewById(R.id.passwordET);
        loginProgress=view.findViewById(R.id.loginProgress);
        rememberMe=view.findViewById(R.id.rememberME);

        onClickListener();
    }

    private void onClickListener() {
        signInBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (validate()) {
                    toggleLoading(true);
                    callApiToLogin(usernameET.getText().toString(), passwordET.getText().toString());
                }
            }
        });


        signUpTV.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                RegisterFragment registerFragment= new RegisterFragment();
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frameLayout,registerFragment).commit();

            }
        });

    }

    private Boolean validate(){
        String username=usernameET.getText().toString();
        String password= passwordET.getText().toString();

        if(username.isEmpty() || password.isEmpty()){
            //Toast.makeText(getActivity(), "Fields should not be left empty!", Toast.LENGTH_SHORT).show();
            Snackbar.make(getView(),"Fields should not be left empty!",Snackbar.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    void callApiToLogin(String email, String password) {
        Call<CustomerLoginResponse> call = ApiClient.getApiService().loginCustomer(email, password);

        call.enqueue(new Callback<CustomerLoginResponse>() {
            @Override
            public void onResponse(Call<CustomerLoginResponse> call, Response<CustomerLoginResponse> response) {
              //  toggleLoading(false);
                if (response.isSuccessful()) {
                    CustomerLoginResponse loginResponse = response.body();
                    if (!loginResponse.getError()) {

                       SharedPreferencesUtils.setStringPreference(getContext(), Constants.API_KEY_KEY, loginResponse.getApikey());

//                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.CUSTOMER_ID, loginResponse.getCustomerId().toString());
//                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.NAME, loginResponse.getName());
//                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.EMAIL, loginResponse.getEmail());
//                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.CONTACT, loginResponse.getPhoneNum());
//                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.PROFILE_PIC, loginResponse.getProfilePic());
                        Intent intent = new Intent(getActivity(), HomeActivity.class);
                        Toast.makeText(getActivity(), "You are Successfully logged in", Toast.LENGTH_LONG).show();
                        startActivity(intent);
//                        if (rememberMe.isChecked()) {
                           SharedPreferencesUtils.setBooleanPreference(getContext(), Constants.IS_LOGGED_IN_KEY, true);
//                        }
                }
//                    else {
//                       // Toast.makeText(getActivity(), "Incorrect Login Credentials", Toast.LENGTH_SHORT).show();
//                        Snackbar.make(getView(),"Wrong details! Please input correct details!",Snackbar.LENGTH_LONG).show();
//                    }
                }
            }

            @Override
            public void onFailure(Call<CustomerLoginResponse> call, Throwable t) {
                toggleLoading(false);
            }
        });


    }
    void toggleLoading(Boolean isLoading) {
        if (isLoading)
            loginProgress.setVisibility(View.VISIBLE);
        else
            loginProgress.setVisibility(View.GONE);

    }


}