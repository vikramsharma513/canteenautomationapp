package com.riya.canteenautomationapp.utils;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.responses.CategoryResponseData;

import java.util.List;

public class CategoryAdapter extends RecyclerView.Adapter<CategoryAdapter.ViewHolder> {
    List<CategoryResponseData> categoryResponseDataList;
    Context context;
    LayoutInflater layoutInflater;
    ClickListener clickListeners;

    public CategoryAdapter(List<CategoryResponseData> data, Context context){
        this.categoryResponseDataList=data;
        this.context=context;
        layoutInflater= LayoutInflater.from(context);
    }

    @NonNull
    @Override
    public CategoryAdapter.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view= layoutInflater.inflate(R.layout.layout_category,parent,false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull CategoryAdapter.ViewHolder holder, int position) {
        CategoryResponseData holderlist= categoryResponseDataList.get(position);
        holder.category.setText(holderlist.getCategoryName());
    }

    @Override
    public int getItemCount() {
        return categoryResponseDataList.size();
    }

    public interface ClickListener {
        void onCategoryClicked(int position);
    }

    public void setClickListeners(ClickListener clickListeners){
        this.clickListeners= clickListeners;
    }

    public class ViewHolder extends RecyclerView.ViewHolder {
        Button category;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            category=itemView.findViewById(R.id.category);

            category.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    clickListeners.onCategoryClicked(getAdapterPosition());
                }
            });
        }
    }
}
