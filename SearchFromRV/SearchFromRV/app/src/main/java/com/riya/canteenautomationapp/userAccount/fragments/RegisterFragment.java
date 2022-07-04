package com.riya.canteenautomationapp.userAccount.fragments;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.google.android.material.snackbar.Snackbar;
import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.api.ApiClient;
import com.riya.canteenautomationapp.responses.CustomerRegisterResponse;
import com.riya.canteenautomationapp.userAccount.UserAccountActivity;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterFragment extends Fragment {
    TextView signInTV;
    EditText nameET,passwordET,emailET, contactET;
    Button signUpBtn;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_register, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        nameET=view.findViewById(R.id.nameET);
        passwordET=view.findViewById(R.id.passwordET);
        emailET=view.findViewById(R.id.emailET);
        contactET=view.findViewById(R.id.contactET);
        signInTV=view.findViewById(R.id.sigInTV);
        signUpBtn=view.findViewById(R.id.signUpBtn);
        onClickListener();

    }

    private void onClickListener() {
        signInTV.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                CustomerLoginFragment loginFragment = new CustomerLoginFragment();
                getActivity().getSupportFragmentManager().beginTransaction().replace(R.id.frameLayout, loginFragment).commit();
            }
        });

        signUpBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(validate()){
                   // Toast.makeText(getActivity(),"Successfully registered",Toast.LENGTH_LONG).show();
                    callApiToRegister(nameET.getText().toString(),emailET.getText().toString(),passwordET.getText().toString(),
                            contactET.getText().toString());

                }
            }
        });
    }

    private boolean validate(){
        String name=nameET.getText().toString();
        String password= passwordET.getText().toString();
        String email= emailET.getText().toString();
        String contact= contactET.getText().toString();

        if(name.isEmpty()||password.isEmpty()||email.isEmpty()||contact.isEmpty()){
            Snackbar.make(getView(),"The required fields are empty",Snackbar.LENGTH_LONG).show();
            return false;
        }else{
            if(password.length()>=6){

                    if(contact.length()==10){
                        return true;
                    }else{
                        contactET.setError("The phone number must be 10 digits");
                        return false;
                    }

            }else{
                passwordET.setError("The password must be greater than 5");
                return false;

            }

        }
    }

     public void callApiToRegister(String name, String email, String password, String phoneNum){
        Call<CustomerRegisterResponse> registerResponseCall= ApiClient.getApiService()
                .registerCustomer(name, email, password, phoneNum);

        registerResponseCall.enqueue(new Callback<CustomerRegisterResponse>() {
            @Override
            public void onResponse(Call<CustomerRegisterResponse> call, Response<CustomerRegisterResponse> response) {
                if (response.isSuccessful()){
                    CustomerRegisterResponse  registerResponse= response.body();

                    if (!registerResponse.getError()) {
                        Toast.makeText(getActivity(), registerResponse.getMessage(), Toast.LENGTH_LONG).show();
                        Intent intent = new Intent(getActivity(), UserAccountActivity.class);
                        startActivity(intent);
                    }
//                    else {
//
//                        Snackbar.make(getView(),registerResponse.getMessage(),Snackbar.LENGTH_LONG).show();
//                    }
                }
            }

            @Override
            public void onFailure(Call<CustomerRegisterResponse> call, Throwable t) {

            }
        });

    }
}