package com.riya.canteenautomationapp.customerSection;

import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.responses.FoodItemResponseData;
import com.riya.canteenautomationapp.utils.Constants;
import com.squareup.picasso.Picasso;

public class ItemDisplayActivity extends AppCompatActivity {

    TextView itemName, itemDesc, category, price, backBtn,quantity;
    ImageView foodImage;
    FoodItemResponseData foodItemResponseData;
    public static String POST_DATA_KEY="data";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_item_display);
        foodItemResponseData= (FoodItemResponseData) getIntent().getSerializableExtra(POST_DATA_KEY);

        itemName= findViewById(R.id.itemNameTV);
        itemDesc=findViewById(R.id.descriptionTV);
        category=findViewById(R.id.categoryTV);
        price=findViewById(R.id.priceTV);
        foodImage=findViewById(R.id.foodIV);
        backBtn=findViewById(R.id.backBtn);
        quantity=findViewById(R.id.quantityTV);

        itemName.setText(foodItemResponseData.getFoodName());
        itemDesc.setText(foodItemResponseData.getFoodDesc());
        category.setText("Category: "+foodItemResponseData.getCategoryName());
        quantity.setText("Quantity: "+foodItemResponseData.getQuantity().toString());
        price.setText("RS: "+foodItemResponseData.getPrice().toString());
        Picasso.get().load(Constants.BASE_URL+foodItemResponseData.getImageUrl()).into(foodImage);

        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

    }

}