package com.riya.canteenautomationapp.customerSection.fragments;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.userAccount.UserAccountActivity;
import com.riya.canteenautomationapp.utils.Constants;
import com.riya.canteenautomationapp.utils.SharedPreferencesUtils;

import de.hdodenhof.circleimageview.CircleImageView;


public class SettingsFragment extends Fragment {
    Button logoutBtn;
    CardView profileCard,aboutCard,orderHistoryCard;
    CircleImageView setprofile;
    TextView aboutUsDesTV, up,down, setname, setemail;
    boolean showAboutContent = true;
    AlertDialog.Builder builder;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_settings, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        logoutBtn= view.findViewById(R.id.logoutBtn);
        profileCard=view.findViewById(R.id.profileCard);
        aboutCard=view.findViewById(R.id.aboutCard);
        setprofile=view.findViewById(R.id.setprofile);
        setemail=view.findViewById(R.id.setemail);
        setname=view.findViewById(R.id.setname);
        orderHistoryCard=view.findViewById(R.id.orderHistoryCard);
        up=view.findViewById(R.id.upArrow);
        down=view.findViewById(R.id.downArrow);
        aboutUsDesTV=view.findViewById(R.id.aboutUsDesTV);
        onClickListeners();
        setMethod();
    }

    private void setMethod() {
        setname.setText(SharedPreferencesUtils.getStringPreference(getContext(),Constants.NAME));
        setemail.setText(SharedPreferencesUtils.getStringPreference(getContext(),Constants.EMAIL));


    }

    @Override
    public void onResume() {
        super.onResume();
        setname.setText(SharedPreferencesUtils.getStringPreference(getContext(),Constants.NAME));
        setemail.setText(SharedPreferencesUtils.getStringPreference(getContext(),Constants.EMAIL));

    }

    private void onClickListeners() {
        logoutBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                confirmation();
            }
        });





        aboutCard.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(showAboutContent){
                    aboutUsDesTV.setVisibility(View.VISIBLE);
                    down.setVisibility(View.GONE);
                    up.setVisibility(View.VISIBLE);
                    showAboutContent=false;
                }else{
                    aboutUsDesTV.setVisibility(View.GONE);
                    up.setVisibility(View.GONE);
                    down.setVisibility(View.VISIBLE);
                    showAboutContent=true;
                }

            }
        });
    }

    private void confirmation() {
        builder= new AlertDialog.Builder(getContext());
        //Setting message manually and performing action on button click
        builder.setMessage("Do you want to logout?")
                .setCancelable(false)
                .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        SharedPreferencesUtils.setBooleanPreference(getContext(), Constants.IS_LOGGED_IN_KEY, false);
                        SharedPreferencesUtils.setStringPreference(getContext(), Constants.API_KEY_KEY, "");
                        Intent intent=new Intent(getActivity(), UserAccountActivity.class);
                        startActivity(intent);
                        getActivity().finish();
                    }
                })
                .setNegativeButton("No", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        //  Action for 'NO' Button
                        dialog.cancel();
                    }
                });
        //Creating dialog box
        AlertDialog alert = builder.create();
        //Setting the title manually
        alert.setTitle("LogoutConfirmation");
        alert.show();

    }
}