package com.riya.canteenautomationapp.customerSection;

import android.os.Bundle;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.customerSection.fragments.MainFragment;
import com.riya.canteenautomationapp.customerSection.fragments.OrderCartFragment;
import com.riya.canteenautomationapp.customerSection.fragments.SettingsFragment;

public class HomeActivity extends AppCompatActivity {
    BottomNavigationView bottom_nav;

    MainFragment mainFragment;
    OrderCartFragment orderCartFragment;
    SettingsFragment settingsFragment;
    Fragment currentFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        bottom_nav=findViewById(R.id.nav_bottom);
        mainFragment= new MainFragment();
        orderCartFragment = new OrderCartFragment();
        settingsFragment = new SettingsFragment();
        currentFragment= mainFragment;

        getSupportFragmentManager().beginTransaction().add(R.id.frame_layout,currentFragment).commit();

        BottomNavigationView.OnNavigationItemSelectedListener navigationItemSelectedListener=
                new BottomNavigationView.OnNavigationItemSelectedListener() {
                    @Override
                    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                        if(item.getItemId()== R.id.nav_home){
                            changeFragment(mainFragment);
                            return true;
                        }
                        else if(item.getItemId()== R.id.nav_order_cart){
                            changeFragment(orderCartFragment);

                        }
                        else if(item.getItemId()== R.id.nav_settings){
                            changeFragment(settingsFragment);
                            return true;
                        }
                        return false;
                    }
                };
        bottom_nav.setOnNavigationItemSelectedListener(navigationItemSelectedListener);
    }

    private void changeFragment(Fragment fragment) {
        if (fragment == currentFragment) {
            return;
        }
        getSupportFragmentManager().beginTransaction().hide(currentFragment).commit();
        if (fragment.isAdded())
            getSupportFragmentManager().beginTransaction().show(fragment).commit();
        else
            getSupportFragmentManager().beginTransaction().add(R.id.frame_layout, fragment, "homeFragments").commit();
        currentFragment = fragment;

    }
}