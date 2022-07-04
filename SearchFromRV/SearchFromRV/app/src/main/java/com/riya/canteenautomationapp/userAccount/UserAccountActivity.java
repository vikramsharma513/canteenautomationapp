package com.riya.canteenautomationapp.userAccount;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.userAccount.fragments.CustomerLoginFragment;

public class UserAccountActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_account);

        CustomerLoginFragment customerLoginFragment= new CustomerLoginFragment();

        getSupportFragmentManager().beginTransaction().add(R.id.frameLayout,customerLoginFragment).commit();
    }
}