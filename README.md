## 接口说明

#### 登录

> **账号：** admin
>
> **密码：** admin

#### 采集接口

```
http://your_apath//collect
```

**返回信息：**

> - **status**: `success`表示采集成功，`error`表示采集失败
> - **real_time_data**: 实时消息数量
> - **new_data**: 实时消息中新增消息数量
> - **invalid_data**: 全部失效数据数量
> - **time**: 采集耗时

**示例：**
```
{
    status: "success",
    real_time_data: 218,
    new_data: 0,
    invalid_data: 275,
    time: "5.9221"
}
```

#### 数据查询接口

```
# 查询一个球队
http://your_apath/api/query?team_name=篮网
# 查询多个球队
http://your_apath/api/query?team_name[]=篮网&team_name[]=老鹰&……
```