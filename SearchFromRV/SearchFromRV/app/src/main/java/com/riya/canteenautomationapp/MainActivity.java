package com.riya.canteenautomationapp;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import androidx.appcompat.app.AppCompatActivity;

import com.riya.canteenautomationapp.customerSection.HomeActivity;
import com.riya.canteenautomationapp.userAccount.UserAccountActivity;
import com.riya.canteenautomationapp.utils.Constants;
import com.riya.canteenautomationapp.utils.SharedPreferencesUtils;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Intent intent= new Intent(this, UserAccountActivity.class);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                boolean is_logged=
                        SharedPreferencesUtils.getBooleanPreference(MainActivity.this, Constants.IS_LOGGED_IN_KEY,false);

                if(is_logged){
                    startActivity(new Intent(getApplicationContext(), HomeActivity.class));
                }
                else{
                    startActivity(intent);
                }
                finish();
            }
        },1000);
    }

}