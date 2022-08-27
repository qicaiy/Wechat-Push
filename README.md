# Wechat-Push
微信公众号定时推送
# 微信公众平台配置  
模板->新建模板->标题填写定时推送或自定义  
模板内容:  
```今天是: {{date.DATA}} 
天气: {{weather.DATA}} 
最高气温: {{max.DATA}} °C 
最低气温: {{mini.DATA}} °C 
实时温度: {{temperature.DATA}} °C 
湿度:{{humidity.DATA}} 
风向: {{daywind.DATA}} 
风力: {{daypower.DATA}} 级 
距离下一次生日还有: {{birthday.DATA}} 天 
你已经: {{sui.DATA}} 岁了 
天气数据更新时间: {{updateTime.DATA}}
